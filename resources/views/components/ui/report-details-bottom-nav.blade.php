@props([
    'reportId' => null,
    'notes' => null,
])
<native:bottom-nav>
    <native:bottom-nav-item
        id="reports"
        icon="home"
        label="{{__('Reports')}}"
        url="/reports"
        active="{{request()->routeIs('home')}}"
    />
    <native:bottom-nav-item
        id="notes"
        icon="chat"
        label="{{__('Notes')}}"
        url="/reports/{{$reportId}}/notes"
        active="{{request()->routeIs('reports.details.notes')}}"
        badge="{{$notes}}"
    />

    <native:bottom-nav-item
        id="fix"
        icon="bolt"
        label="{{__('Fix!t')}}"
        url="/reports/{{$reportId}}/edit"
        active="{{request()->routeIs('home')}}"
    />

    @if(!request()->routeIs('reports.details'))
        <native:bottom-nav-item
            id="fix"
            icon="description"
            label="{{__('Report')}}"
            url="/reports/{{$reportId}}"
            active="{{request()->routeIs('reports.details')}}"
        />
    @endif

    @if(request()->routeIs('reports.details'))
        <native:bottom-nav-item
            id="addimage"
            icon="photo"
            label="{{__('Add')}}"
            url="/reports/{{$reportId}}/imageadd"
            active="{{request()->routeIs('reports.details.imageadd')}}"
        />
    @endif

    <native:bottom-nav-item
        id="edit"
        icon="edit"
        label="{{__('Edit')}}"
        url="/reports/{{$reportId}}/edit"
        active="{{request()->routeIs('home')}}"
    />

</native:bottom-nav>
