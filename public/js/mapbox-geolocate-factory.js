/**
 * MapboxGeolocateControlFactory
 *
 * Factory for creating OS-specific geolocate controls for Mapbox GL JS maps.
 * Handles the differences between iOS (native Mapbox control) and Android (custom NativePHP control).
 */
window.MapboxGeolocateControlFactory = {
    /**
     * Create a unified geolocate control that works across platforms
     * @param {string} os - Operating system ('ios', 'android', or other)
     * @param {Object} options - Configuration options
     * @param {Function} options.onLocationReceived - Callback when location is received (lat, lng)
     * @param {Function} options.onError - Callback when error occurs
     * @param {string} options.componentId - Livewire component ID for permission handling
     * @param {boolean} options.trackUserLocation - Track user location (default: false)
     * @param {boolean} options.showUserHeading - Show user heading (default: false)
     * @param {boolean} options.showAccuracyCircle - Show accuracy circle (default: false)
     * @param {boolean} options.showUserLocation - Show user location (default: false)
     * @returns {Object} Mapbox control object
     */
    create: function(os, options = {}) {
        console.log('MapboxGeolocateControlFactory: Creating unified control for OS:', os);

        // Use unified control for all platforms
        return this.createUnifiedControl(os, options);
    },

    /**
     * Create unified geolocate control that works on both iOS and Android
     */
    createUnifiedControl: function(os, options) {
        console.log('Creating unified geolocate control with options:', {
            os: os,
            trackUserLocation: options.trackUserLocation ?? false,
            showUserHeading: options.showUserHeading ?? false,
            showAccuracyCircle: options.showAccuracyCircle ?? false,
            showUserLocation: options.showUserLocation ?? false,
            useNativePermissions: os === 'android'
        });

        const control = new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: options.trackUserLocation ?? false,
            showUserHeading: options.showUserHeading ?? false,
            showAccuracyCircle: options.showAccuracyCircle ?? false,
            showUserLocation: options.showUserLocation ?? false
        });

        // Set up event listeners
        if (options.onLocationReceived) {
            control.on('geolocate', function(e) {
                const lat = e.coords.latitude;
                const lng = e.coords.longitude;
                console.log('Unified control geolocate triggered:', lat, lng);
                options.onLocationReceived(lat, lng);
            });
        }

        if (options.onError) {
            control.on('error', function(e) {
                console.error('Unified control geolocate error:', e);
                options.onError(e);
            });
        }

        // For Android, wrap the control to handle native permissions
        if (os === 'android') {
            this.wrapControlForNativePermissions(control, options);
        }

        return control;
    },

    /**
     * Create a specialized geolocate control for inspection/route tracking
     * Handles both initial positioning and continuous route tracking
     */
    createForInspection: function(os, options) {
        console.log('Creating specialized geolocate control for inspection on:', os);

        // Create the base control with inspection-specific options
        const control = new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 1000
            },
            trackUserLocation: true,
            showUserHeading: true,
            showAccuracyCircle: true,
            showUserLocation: true,
            fitBoundsOptions: {
                maxZoom: 15
            }
        });

        // Apply the same NativePHP wrapper for initial positioning
        if (os === 'android' || os === 'ios') {
            this.wrapControlForNativePermissions(control, options);
        }

        // Override the geolocation APIs for route tracking
        this.wrapGeolocationForRouteTracking(options);

        return control;
    },

    /**
     * Wrap browser geolocation APIs for route tracking with NativePHP
     */
    wrapGeolocationForRouteTracking: function(options) {
        if (!options.componentId || !window.Livewire) {
            console.log('No componentId provided for route tracking, using browser geolocation');
            return;
        }

        console.log('Wrapping geolocation APIs for route tracking with NativePHP');

        // Store original functions
        const originalGetCurrentPosition = navigator.geolocation.getCurrentPosition.bind(navigator.geolocation);
        const originalWatchPosition = navigator.geolocation.watchPosition.bind(navigator.geolocation);

        // Override getCurrentPosition for initial positioning
        navigator.geolocation.getCurrentPosition = function(successCallback, errorCallback, geoOptions) {
            console.log('Route tracking: Intercepted getCurrentPosition, using NativePHP');

            const component = Livewire.find(options.componentId);
            if (component) {
                // Use the same NativePHP approach as the regular geolocate control
                // Set up event listener for location data
                const handleLocationReceived = (event) => {
                    let eventData = event.detail;
                    if (Array.isArray(eventData) && eventData.length > 0) {
                        eventData = eventData[0];
                    }

                    if (eventData && eventData.success && eventData.latitude && eventData.longitude) {
                        const position = {
                            coords: {
                                latitude: eventData.latitude,
                                longitude: eventData.longitude,
                                accuracy: eventData.accuracy || 10,
                                altitude: null,
                                altitudeAccuracy: null,
                                heading: null,
                                speed: null
                            },
                            timestamp: Date.now()
                        };

                        console.log('Route tracking: Initial position received via NativePHP:', position);
                        successCallback(position);
                    } else if (errorCallback) {
                        errorCallback(new Error('Failed to get initial position via NativePHP'));
                    }

                    // Clean up listener
                    document.removeEventListener('livewire:user-location-updated', handleLocationReceived);
                };

                document.addEventListener('livewire:user-location-updated', handleLocationReceived);
                component.call('requestUserLocation');

                // Cleanup timeout
                setTimeout(() => {
                    document.removeEventListener('livewire:user-location-updated', handleLocationReceived);
                }, 10000);
            } else {
                // Fallback to original
                originalGetCurrentPosition(successCallback, errorCallback, geoOptions);
            }
        };

        // Override watchPosition for continuous route tracking
        navigator.geolocation.watchPosition = function(successCallback, errorCallback, geoOptions) {
            console.log('Route tracking: Intercepted watchPosition, using NativePHP continuous tracking');

            // For now, fall back to original watchPosition
            // TODO: Implement NativePHP continuous tracking if needed
            console.log('Using browser watchPosition for continuous tracking (NativePHP continuous tracking not implemented yet)');
            return originalWatchPosition(successCallback, errorCallback, geoOptions);
        };
    },

    /**
     * Wrap a Mapbox control to use NativePHP directly (simplified approach)
     */
    wrapControlForNativePermissions: function(control, options) {
        console.log('Wrapping control for NativePHP direct access');

        // Store the original trigger method
        const originalTrigger = control.trigger.bind(control);

        // Track if a request is in progress to prevent multiple simultaneous requests
        let isRequestInProgress = false;

        // Override the trigger method to use NativePHP directly (back to working approach)
        control.trigger = function() {
            console.log('Android geolocate triggered - using NativePHP directly');

            // Prevent multiple simultaneous requests
            if (isRequestInProgress) {
                console.log('Location request already in progress, ignoring click');
                return;
            }

            if (options.componentId && window.Livewire) {
                try {
                    const component = Livewire.find(options.componentId);
                    if (component) {
                        console.log('Component found, requesting location directly via NativePHP');

                        // Mark request as in progress
                        isRequestInProgress = true;

                        // Manually set Mapbox control to searching state with animation
                        if (control._geolocateButton) {
                            control._geolocateButton.classList.add('mapboxgl-ctrl-geolocate-waiting');
                            //control._geolocateButton.classList.add('animate-pulse');
                            console.log('Set geolocate button to searching state with spin animation');
                        }

                        // Set up timeout first so we can cancel it later
                        let timeoutId = null;

                        // Set up listener for location data from NativePHP
                        const handleLocationReceived = (event) => {
                            console.log('Received location from NativePHP:', event.detail);

                            let eventData = event.detail;
                            if (Array.isArray(eventData) && eventData.length > 0) {
                                eventData = eventData[0];
                            }

                            if (eventData && eventData.success && eventData.latitude && eventData.longitude) {
                                console.log('NativePHP location success, setting up Mapbox visual elements');

                                // Cancel the timeout since we got a successful response
                                if (timeoutId) {
                                    clearTimeout(timeoutId);
                                    console.log('Location received successfully, timeout canceled');
                                }

                                // Create position object that matches browser geolocation API
                                const position = {
                                    coords: {
                                        latitude: eventData.latitude,
                                        longitude: eventData.longitude,
                                        accuracy: eventData.accuracy || 10,
                                        altitude: null,
                                        altitudeAccuracy: null,
                                        heading: null,
                                        speed: null
                                    },
                                    timestamp: Date.now()
                                };

                                console.log('Manually setting up Mapbox geolocate visual elements');

                                // Set control state to active_lock for visual elements
                                if (control._state !== undefined) {
                                    control._state = 'active_lock';
                                    console.log('Set control state to active_lock');
                                }

                                // Update button visual state - remove spinning, set to active
                                if (control._geolocateButton) {
                                    // Remove searching state
                                    control._geolocateButton.classList.remove('mapboxgl-ctrl-geolocate-waiting');
                                    //control._geolocateButton.classList.remove('animate-pulse');

                                    // Set to active/tracking state
                                    control._geolocateButton.classList.remove('mapboxgl-ctrl-geolocate-background');
                                    control._geolocateButton.classList.add('mapboxgl-ctrl-geolocate-active');
                                    control._geolocateButton.classList.add('mapboxgl-ctrl-geolocate-active-error');
                                    console.log('Updated geolocate button to active state (stopped spinning)');
                                }

                                // Fire the geolocate event to trigger visual updates
                                control.fire('geolocate', position);

                                // Try to trigger user location tracking
                                if (control._map && control.options.trackUserLocation) {
                                    console.log('Attempting to show user location dot');

                                    // Try to call internal methods if available
                                    if (control._updateMarker && typeof control._updateMarker === 'function') {
                                        control._updateMarker(position);
                                        console.log('Called control._updateMarker');
                                    }

                                    if (control._updateCamera && typeof control._updateCamera === 'function') {
                                        control._updateCamera(position);
                                        console.log('Called control._updateCamera');
                                    }
                                }

                                // Also call the onLocationReceived callback if provided
                                if (options.onLocationReceived) {
                                    options.onLocationReceived(eventData.latitude, eventData.longitude);
                                }
                            } else {
                                console.error('NativePHP location failed:', eventData);

                                // Cancel timeout for failed responses too
                                if (timeoutId) {
                                    clearTimeout(timeoutId);
                                }

                                // Stop spinning animation on error
                                if (control._geolocateButton) {
                                    control._geolocateButton.classList.remove('mapboxgl-ctrl-geolocate-waiting');
                                    control._geolocateButton.classList.remove('animate-spin');
                                    console.log('Stopped spinning animation due to location error');
                                }

                                if (options.onError) {
                                    options.onError(new Error('Failed to get location via NativePHP'));
                                }
                            }

                            // Clean up listener and reset progress flag
                            document.removeEventListener('livewire:user-location-updated', handleLocationReceived);
                            isRequestInProgress = false;
                        };

                        // Listen for location data from NativePHP
                        document.addEventListener('livewire:user-location-updated', handleLocationReceived);

                        // Request location directly via NativePHP (this will handle permissions automatically)
                        console.log('Requesting location via NativePHP (permissions will be requested automatically)');
                        component.call('requestUserLocation');

                        // Set up cleanup timeout with error handling
                        timeoutId = setTimeout(() => {
                            if (isRequestInProgress) {
                                document.removeEventListener('livewire:user-location-updated', handleLocationReceived);
                                isRequestInProgress = false;
                                console.log('Timeout reached (10s), cleaning up location listener');

                                // Stop spinning animation on timeout
                                if (control._geolocateButton) {
                                    control._geolocateButton.classList.remove('mapboxgl-ctrl-geolocate-waiting');
                                    control._geolocateButton.classList.remove('animate-spin');
                                    console.log('Stopped spinning animation due to timeout');
                                }

                                // Show error if no location was received
                                if (options.onError) {
                                    options.onError(new Error('Location request timed out after 10 seconds'));
                                }
                            }
                        }, 10000);

                    } else {
                        console.error('Could not find Livewire component:', options.componentId);
                        // Fallback to original trigger
                        originalTrigger();
                    }
                } catch (error) {
                    console.error('Error in NativePHP wrapper:', error);
                    // Fallback to original trigger
                    originalTrigger();
                }
            } else {
                console.log('No componentId or Livewire not available, using original trigger');
                // Fallback to original trigger
                originalTrigger();
            }
        };
    },

    /**
     * Create iOS geolocate control using native Mapbox GeolocateControl (LEGACY - keeping for reference)
     */
    createIosControl: function(options) {
        console.log('Creating iOS geolocate control with options:', {
            trackUserLocation: options.trackUserLocation ?? false,
            showUserHeading: options.showUserHeading ?? false,
            showAccuracyCircle: options.showAccuracyCircle ?? false,
            showUserLocation: options.showUserLocation ?? false
        });

        const control = new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: options.trackUserLocation ?? false,
            showUserHeading: options.showUserHeading ?? false,
            showAccuracyCircle: options.showAccuracyCircle ?? false,
            showUserLocation: options.showUserLocation ?? false
        });

        // Listen for geolocation events
        if (options.onLocationReceived) {
            control.on('geolocate', function(e) {
                const lat = e.coords.latitude;
                const lng = e.coords.longitude;
                console.log('iOS geolocate triggered:', lat, lng);
                options.onLocationReceived(lat, lng);
            });
        }

        if (options.onError) {
            control.on('error', function(e) {
                console.error('iOS geolocate error:', e);
                options.onError(e);
            });
        }

        return control;
    },

    /**
     * Create Android geolocate control using custom button that calls NativePHP
     */
    createAndroidControl: function(options) {
        console.log('Creating Android geolocate control');

        return {
            onAdd: function(map) {
                this._map = map;
                this._container = document.createElement('div');
                this._container.className = 'mapboxgl-ctrl mapboxgl-ctrl-group';

                const button = document.createElement('button');
                button.className = 'mapboxgl-ctrl-geolocate';
                button.type = 'button';
                button.title = 'Geolocate';
                button.style.display = 'flex';
                button.style.alignItems = 'center';
                button.style.justifyContent = 'center';

                // Add the geolocate icon
                button.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 2a1 1 0 011 1v1.07A6.002 6.002 0 0115.93 9H17a1 1 0 110 2h-1.07A6.002 6.002 0 0111 15.93V17a1 1 0 11-2 0v-1.07A6.002 6.002 0 014.07 11H3a1 1 0 110-2h1.07A6.002 6.002 0 019 4.07V3a1 1 0 011-1zm0 4a4 4 0 100 8 4 4 0 000-8zm0 2a2 2 0 100 4 2 2 0 000-4z" fill="currentColor"/></svg>';

                button.addEventListener('click', () => {
                    console.log('Android geolocate button clicked');
                    console.log('Options:', options);
                    console.log('Livewire available:', !!window.Livewire);

                    // Try to find and call the Livewire component method
                    if (options.componentId && window.Livewire) {
                        try {
                            console.log('Attempting to find component:', options.componentId);
                            const component = Livewire.find(options.componentId);
                            console.log('Component found:', !!component);

                            if (component) {
                                console.log('Calling requestUserLocation on component:', options.componentId);
                                console.log('Component methods available:', Object.getOwnPropertyNames(component));
                                component.call('requestUserLocation');
                            } else {
                                console.error('Could not find Livewire component:', options.componentId);
                                console.log('Available components:', Object.keys(Livewire.all()));
                            }
                        } catch (error) {
                            console.error('Error calling Livewire component:', error);
                        }
                    } else {
                        console.log('No componentId or Livewire not available, trying fallback');
                        // Fallback: try to find any component with requestUserLocation method
                        const wireElements = document.querySelectorAll('[wire\\:id]');
                        console.log('Found wire elements:', wireElements.length);

                        for (let element of wireElements) {
                            try {
                                const componentId = element.getAttribute('wire:id');
                                console.log('Trying fallback component:', componentId);
                                const component = Livewire.find(componentId);
                                if (component && typeof component.call === 'function') {
                                    console.log('Calling requestUserLocation on fallback component:', componentId);
                                    component.call('requestUserLocation');
                                    break;
                                }
                            } catch (error) {
                                console.log('Fallback component call failed:', error);
                            }
                        }
                    }
                });

                this._container.appendChild(button);
                return this._container;
            },

            onRemove: function() {
                if (this._container && this._container.parentNode) {
                    this._container.parentNode.removeChild(this._container);
                }
                this._map = undefined;
            }
        };
    },

    /**
     * Helper method to set up location received callback for Android
     * This should be called from Livewire components when location is received
     */
    triggerLocationReceived: function(lat, lng, mapId) {
        console.log('MapboxGeolocateControlFactory: Location received:', lat, lng, 'for map:', mapId);

        // Dispatch custom event that maps can listen to
        document.dispatchEvent(new CustomEvent('mapbox-location-received', {
            detail: {
                lat: lat,
                lng: lng,
                mapId: mapId
            }
        }));
    }
};

console.log('MapboxGeolocateControlFactory loaded');
