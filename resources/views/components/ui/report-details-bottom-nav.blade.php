@props([
    'reportId' => null,
])
<native:bottom-nav>
    <native:bottom-nav-item
        id="notes"
        icon="chat"
        label="{{__('Notes')}}"
        url="/reports/{{$reportId}}/notes"
        active="{{request()->routeIs('reports.details.notes')}}"
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

    <native:bottom-nav-item
        id="worklog"
        icon="list"
        label="{{__('Worklog')}}"
        url="/reports/{{$reportId}}/edit"
        active="{{request()->routeIs('home')}}"
    />

    <native:bottom-nav-item
        id="edit"
        icon="edit"
        label="{{__('Edit')}}"
        url="/reports/{{$reportId}}/edit"
        active="{{request()->routeIs('home')}}"
    />
</native:bottom-nav>
