<div class="page-head">
    <span>Class: "{{$class->name}}"</span><br/>
    <div class='help-block'>
        Select a Series to view Class Report by Lesson.
    </div>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                    <tr>
                        @foreach ($series as $serie)
                            <th style="text-align: center">
                                <a href="{{ URL::route('admin.reports.classes.scores_by_lesson', array($class->id, $serie->id) ) }}">
                                    {{$serie->title}}
                                </a>
                            </th>
                        @endforeach
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
