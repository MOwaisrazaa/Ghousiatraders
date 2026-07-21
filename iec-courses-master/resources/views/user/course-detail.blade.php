@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar: Lectures List -->
        <div class="col-md-3 border-end">
            <h5 class="mt-3">{{ $course->name }}</h5>
            <ul class="list-group mb-3">
                @foreach($course->lectures as $lecture)
                    <li class="list-group-item">
                        <a href="#lecture-{{ $lecture->id }}">{{ $lecture->lecture_title }}</a>
                    </li>
                @endforeach
            </ul>
            <h6>Course PDFs</h6>
            <ul class="list-group">
                @foreach($course->pdfs as $pdf)
                    <li class="list-group-item">
                        <a href="{{ Storage::url($pdf->file_path) }}" target="_blank">{{ $pdf->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Main Content: Lectures and PDFs -->
        <div class="col-md-9">
            @foreach($course->lectures as $lecture)
                <div id="lecture-{{ $lecture->id }}" class="mb-4">
                    <h4>{{ $lecture->lecture_title }}</h4>
                    <div>{!! $lecture->content !!}</div>
                    <!-- Related PDFs -->
                    <div>
                        <h6>Related PDFs</h6>
                        <ul>
                            @foreach($lecture->pdfs as $pdf)
                                <li>
                                    <a href="{{ Storage::url($pdf->file_path) }}" target="_blank">{{ $pdf->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Q&A Chat -->
                    @livewire('lecture-qa', ['lectureId' => $lecture->id])
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
