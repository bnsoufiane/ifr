<div class="page-head">
    <h2 class="orange_color">"{{ $class->name }}" Grades</h2>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.classes.index') }}">Classes</a></li>
        <li>{{ $class->name }}</li>
        <li class="active">Grades</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                    <tr>
                        <th style="width: 20%"></th>
                        @foreach ($lessons as $lesson)
                        <th colspan="2" class="lesson-name">
                            {{ $lesson->title }}
                        </th>
                        @endforeach
                    </tr>
                    <tr>
                        <th style="width: 20%;">Student</th>

                        @foreach ($lessons as $lesson)
                        <th>Progress</th>
                        <th>Score</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="no-border-x">
                    @foreach ($students as $student)
                    <tr>
                        <td class="student-name">
                            <a href="{{ URL::route('admin.classes.student_grades', array($class->id, $student->id())) }}">
                                {{ $student->formattedName() }}
                            </a>
                        </td>

                        @foreach ($lessons as $lesson)
                        <td>{{ $student->answersTo($lesson)->progress() }}</td>
                        <td>{{ $student->answersTo($lesson)->grade() }}%</td>
                        @endforeach
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script type="text/javascript">
    $(function() {
        

    });
</script>
@stop