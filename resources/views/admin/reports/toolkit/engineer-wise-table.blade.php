<table class="table table-condensed">

    <thead>
        <tr>
            <th>#</th>
            <th> Engineer Name</th>
            <th> Assigned Date</th>
            <th> Total Assigned </th>
            @if(!request("export"))
                <th> Details</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @php 
            $i=1 
        @endphp
        @foreach($user_wise_toolkits as $key => $engineer)
        @php
            $grouped_by_toolkits = $engineer->assigned_toolkits->groupBy("tool_kit_id");
            $grouped_by_toolkits_datewise = $engineer->assigned_toolkits->groupBy("assign_date");
            $assigned_date = "";
            foreach ($grouped_by_toolkits_datewise as $date => $value) {
                $assigned_date = date("d-m-Y",  strtotime($date));;
                break;
            }
        @endphp
        <tr>
            <td>{{ $i }}</td>
            <td>{{ ucwords($engineer->full_name()) }}</td>
            <td>{{ $assigned_date}}</td>
            <td>{{ $grouped_by_toolkits->count() }}</td>
            @if(!request("export"))
            <td>
                <a href="{{route('view-user-assigned-toolkit-reports.store', ["user_id" => $engineer->id])}}" class="bt btn-primary btn-sm"><i class="fa fa-eye"></i> View</a>
            </td>
            @endif
        </tr>
        @php $i++ @endphp
        @endforeach
    </tbody>
</table>