<div class="page-head">
    <h2 class="orange_color">"{{ $class->name }}" Products</h2>
    <?php 
        if($cs){
    ?>
            <div class='help-block'>
                Here you can view the grade report for all of the students in a particular class.  Select the product, series, and lesson to see the students and their progress and score.
            </div>
    <?php 
        }
        else{
          ?>
          <div class='help-block'>
                Select a product you would like to view.  You will select series and lessons next.
          </div>
            <?php 
        }
    ?>
    
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <?php 
            $currentUser = \Sentry::getUser();
            $isTeacher = $currentUser->isTeacher();
            $url = URL::route('admin.reports.index');
            if(!$isTeacher && !$cs ){
                echo '<li><a href="'.$url.'">Teachers</a></li>';
                $classesUrl = URL::route('admin.classes.grades.teacher_classes', array($teacher_id));
            }else{
                $classesUrl = URL::route('admin.reports.index');
            }
            
        ?>
        <li><a href="{{ ($cs)? (URL::route('admin.classes.index')):($classesUrl) }}">Classes</a></li>
        <li>{{ $class->name }}</li>
        <li class="active">Products</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                    <tr>
                        <th style="width: 60%;">Products List</th>
                    </tr>
                </thead>
                <tbody class="no-border-x">
                    @foreach ($modules as $module)
                    <tr>
                        <td class="student-name">
                            <?php
                            if ($cs && $teacher_id) {
                                $arr = array($class->id, $module->id, 'cs' => $cs, 'teacher' => $teacher_id);
                            } else if ($cs) {
                                $arr = array($class->id, $module->id, 'cs' => $cs);
                            } else if ($teacher_id) {
                                $arr = array($class->id, $module->id, 'teacher' => $teacher_id);
                            } else {
                                $arr = array($class->id, $module->id);
                            }
                            ?>
                            <a href="{{ URL::route('admin.classes.grades.series', $arr) }}">
                                {{ $module->title }}
                            </a>
                        </td>

                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@section('scripts')
<script type="text/javascript">
    $(function() {
        $('table').dataTable({
            paging: true,
            columns: [null, ],
            dom: 'T<"clear">lfrtip',
            tableTools: {
                "sSwfPath": "{{ asset('copy_csv_xls_pdf.swf') }}",
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "mColumns": [0]
                    },
                    {
                        "sExtends": "xls",
                        "sButtonText": "Export Excel",
                        "mColumns": [0]
                    },
                    {
                        "sExtends": "pdf",
                        "sButtonText": "Export PDF",
                        "mColumns": [0]
                    }
                ]
            }
        });

    });
</script>
@stop
