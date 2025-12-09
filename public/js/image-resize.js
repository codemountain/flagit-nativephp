/**
 * Image Compression Module using Canvas API
 * Reliable image resizing for mobile photos without external dependencies
 */

window.ImageCompressor = {
    /**
     * Compress an image file using Canvas API
     * @param {File|Blob} file - The image file to compress
     * @param {Object} options - Compression options
     * @param {number} options.maxWidth - Maximum width (default: 1920)
     * @param {number} options.maxHeight - Maximum height (default: 1920)
     * @param {number} options.quality - JPEG quality 0-1 (default: 0.85)
     * @param {number} options.maxSizeMB - Maximum file size in MB (default: 2)
     * @returns {Promise<Object>} Result object with success status and blob/error
     */
    async compress(file, options = {}) {
        const maxWidth = options.maxWidth || 1920;
        const maxHeight = options.maxHeight || 1920;
        const quality = options.quality || 0.85;
        const maxSizeMB = options.maxSizeMB || 2; // 2MB max size

        try {
            // Check if file is HEIC format (not supported by Canvas)
            const fileName = file.name ? file.name.toLowerCase() : '';
            const fileType = file.type ? file.type.toLowerCase() : '';

            if (fileName.endsWith('.heic') || fileName.endsWith('.heif') ||
                fileType.includes('heic') || fileType.includes('heif')) {
                return {
                    success: false,
                    error: 'HEIC format not supported. Please change iPhone camera settings to "Most Compatible" format.'
                };
            }

            // Load the image
            const img = await this.loadImage(file);

            // Calculate new dimensions maintaining aspect ratio
            const { width, height } = this.calculateDimensions(
                img.width,
                img.height,
                maxWidth,
                maxHeight
            );

            // Create canvas and draw resized image
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            canvas.width = width;
            canvas.height = height;

            // Use better quality settings for Canvas
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';

            // Draw the image
            ctx.drawImage(img, 0, 0, width, height);

            // Try different quality levels to get under max size
            let blob = null;
            let currentQuality = quality;
            let attempts = 0;
            const maxAttempts = 5;

            while (attempts < maxAttempts) {
                blob = await this.canvasToBlob(canvas, currentQuality);

                if (blob.size <= maxSizeMB * 1024 * 1024) {
                    break; // Size is acceptable
                }

                // Reduce quality for next attempt
                currentQuality *= 0.85;
                attempts++;

                console.log(`Attempt ${attempts}: Size ${this.formatBytes(blob.size)}, reducing quality to ${(currentQuality * 100).toFixed(0)}%`);
            }

            // If still too large after quality reduction, try reducing dimensions
            if (blob.size > maxSizeMB * 1024 * 1024) {
                console.log('Image still too large, reducing dimensions...');

                // Reduce dimensions by 25%
                const reducedWidth = Math.round(width * 0.75);
                const reducedHeight = Math.round(height * 0.75);

                canvas.width = reducedWidth;
                canvas.height = reducedHeight;

                ctx.imageSmoothingEnabled = true;
                ctx.imageSmoothingQuality = 'high';
                ctx.drawImage(img, 0, 0, reducedWidth, reducedHeight);

                blob = await this.canvasToBlob(canvas, currentQuality);
            }

            // Final check
            if (blob.size > maxSizeMB * 1024 * 1024) {
                return {
                    success: false,
                    error: `Unable to compress image below ${maxSizeMB}MB. Please take a lower resolution photo.`
                };
            }

            // Log compression results
            const originalSize = file.size || 0;
            const compressedSize = blob.size;
            const reduction = Math.round((1 - compressedSize / originalSize) * 100);

            console.log('Canvas compression successful:', {
                originalSize: this.formatBytes(originalSize),
                compressedSize: this.formatBytes(compressedSize),
                reduction: reduction + '%',
                dimensions: `${canvas.width}x${canvas.height}`,
                finalQuality: (currentQuality * 100).toFixed(0) + '%'
            });

            return {
                success: true,
                blob: blob,
                width: canvas.width,
                height: canvas.height,
                originalSize: originalSize,
                compressedSize: compressedSize
            };

        } catch (error) {
            console.error('Canvas compression failed:', error);
            return {
                success: false,
                error: error.message || 'Compression failed'
            };
        }
    },

    /**
     * Convert canvas to blob with specified quality
     * @param {HTMLCanvasElement} canvas - The canvas element
     * @param {number} quality - JPEG quality (0-1)
     * @returns {Promise<Blob>} The compressed blob
     */
    canvasToBlob(canvas, quality) {
        return new Promise((resolve, reject) => {
            canvas.toBlob(
                (blob) => {
                    if (blob) {
                        resolve(blob);
                    } else {
                        reject(new Error('Failed to create blob from canvas'));
                    }
                },
                'image/jpeg',
                quality
            );
        });
    },

    /**
     * Load an image from a File or Blob
     * @param {File|Blob} file - The image file
     * @returns {Promise<HTMLImageElement>} The loaded image element
     */
    loadImage(file) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            const url = URL.createObjectURL(file);

            img.onload = () => {
                URL.revokeObjectURL(url); // Clean up memory
                resolve(img);
            };

            img.onerror = () => {
                URL.revokeObjectURL(url);

                // Check if it might be HEIC based on error
                const fileName = file.name ? file.name.toLowerCase() : '';
                if (fileName.endsWith('.heic') || fileName.endsWith('.heif')) {
                    reject(new Error('HEIC format not supported. Please change camera settings to "Most Compatible".'));
                } else {
                    reject(new Error('Failed to load image. Format may not be supported.'));
                }
            };

            img.src = url;
        });
    },

    /**
     * Calculate new dimensions maintaining aspect ratio
     * @param {number} srcWidth - Original width
     * @param {number} srcHeight - Original height
     * @param {number} maxWidth - Maximum allowed width
     * @param {number} maxHeight - Maximum allowed height
     * @returns {Object} New width and height
     */
    calculateDimensions(srcWidth, srcHeight, maxWidth, maxHeight) {
        let width = srcWidth;
        let height = srcHeight;

        // Calculate scaling factor to fit within max dimensions
        if (width > maxWidth || height > maxHeight) {
            const widthRatio = maxWidth / width;
            const heightRatio = maxHeight / height;
            const ratio = Math.min(widthRatio, heightRatio);

            width = Math.round(width * ratio);
            height = Math.round(height * ratio);
        }

        return { width, height };
    },

    /**
     * Convert blob to base64 string
     * @param {Blob} blob - The blob to convert
     * @returns {Promise<string>} Base64 data URL
     */
    async blobToBase64(blob) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();

            reader.onloadend = () => {
                resolve(reader.result);
            };

            reader.onerror = () => {
                reject(new Error('Failed to convert blob to base64'));
            };

            reader.readAsDataURL(blob);
        });
    },

    /**
     * Format bytes to human readable string
     * @param {number} bytes - Number of bytes
     * @returns {string} Formatted string (e.g., "1.5 MB")
     */
    formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
};

// Export for module usage if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = window.ImageCompressor;
}