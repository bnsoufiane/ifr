<div class="page-head">
    <h2 class="orange_color">Edit a Lesson</h2>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">Products & Series</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">{{ $series->module->title }}</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">{{ $series->title }}</a></li>
        <li class="active">Edit "{{ $lesson->title }}"</li>
    </ol>
</div>

<script>
    var LESSON_ID = {{ $lesson->id }};
</script>

@include('admin/lessons/_form')
