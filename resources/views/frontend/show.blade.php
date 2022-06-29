@extends('layout.skeleton')

@section('title', $sport_arena->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Detail</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>Name</td><td>{{ $sport_arena->name }}</td></tr>
                        <tr><td>Address</td><td>{{ $sport_arena->address }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Location</div>
            @if ($sport_arena->coordinate)
            <div class="card-body" id="mapid"></div>
            @else
            <div class="card-body">Coordinate</div>
            @endif
        </div>
    </div>
</div>

<div class="row justify-content-center mt-5 mb-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Booking Jadwal
            </div>
            <div class="card-body">
                <div class="form-group col-md-4">
                    <form id="form-booking-date" action="{{ route('sport-arena.detail', $sport_arena->id) }}" method="get">
                    <label>Select Date:</label>
                        <div class="input-group date" id="booking-date" data-target-input="nearest">
                            <input type="text" id="booking-date-input" name="booking_date" class="form-control datetimepicker-input" data-date-format="d-M-Y" data-target="#booking-date"/>
                            <div class="input-group-append" data-target="#booking-date" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row justify-content-center">
                    @foreach ($daterange as $date)
                        <div class="col-md-2">
                            <div class="card mb-2">
                                <button class="btn btn-app bg-info">
                                    {{ $date->format('d M') }}
                                    <br>
                                    {{ ucwords($date->format('l')) }}
                                </button>
                            </div>
                            @foreach ($sport_arena->schedules->where('day', strtolower($date->format('l')))->sortBy('time_start') as $schedule)
                                <div class="card mb-2">
                                    <button class="add-cart btn btn-app bg-default" 
                                        data-value="{{ $schedule->id }}" 
                                        data-date="{{ $date->format('Y-m-d') }}" 
                                        data-status="0"
                                        {{ $schedule->notAvailableByDate($date->format('Y-m-d')) ? 'disabled' : '' }}>
                                        Rp. {{ number_format($schedule->price) }}
                                        <br>
                                        {{ date("H:i", strtotime($schedule->time_start)) }} - {{ date("H:i", strtotime($schedule->time_end)) }}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                
                <form id="book-form" action="{{ route('booking.confirmation', $sport_arena->id) }}" method="get">
                    <input id="book-value" type="hidden" name="time_booking" value="">
                    <button id="book-btn" type="submit" class="btn btn-block bg-primary" style="color: white; font-weight: bold; display: none;">
                        Book Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>

<style>
    #mapid { height: 400px; }
</style>
@endsection
@push('scripts')
<!-- InputMask -->
<script src="{{ asset('AdminLTE/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/inputmask/jquery.inputmask.min.js') }}"></script>

<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin=""></script>

<script>
    let getData = [] ;
    $(function() {
        //Date picker
        @if (request("booking_date"))
            let getCurrentDate = {!! json_encode(request("booking_date")) !!}
        @else
            let getCurrentDate = new Date()
        @endif
        
       $('#booking-date').datetimepicker({
            date: getCurrentDate,
            format: 'D-MMM-Y'
        });

        $("#booking-date").on("change.datetimepicker", function() {
            console.log($(this))
            $('#form-booking-date').submit();
        });

        $( ".add-cart" ).click(function() {
            if ($(this).data("status") == "0") {   
                $(this).data('status', "1");  
                $(this).addClass("bg-success")
                getData.push({"schedule_id": $(this).data("value"), "date": $(this).data("date")});
            } else {
                $(this).data('status', 0);
                $(this).removeClass("bg-success")
                getData.splice(getData.indexOf($(this).data("schedule_id")), 1);
            }

            bookButton();
            updateBookId();
        });

        $("#book-form").on('submit', function() {
            event.preventDefault();

            $.ajax({
				url: "{{ route('booking.add-cart') }}",
				method: "GET",
				dataType: "HTML",
				data: {'data':JSON.stringify(getData)},
				success: function (response) {
                    window.location.href = "{{ route('booking.confirmation', $sport_arena->id) }}";
				},
				error: function () {

				}
			});
        });
    });

    function bookButton()
    {
        if (getData.length > 0) {
            $("#book-btn").show()
        } else {
            $("#book-btn").hide()
        }
    }

    function updateBookId()
    {
        $('#book-value').attr('value', getData);
        console.log(getData)
    }

    var map = L.map('mapid').setView([{{ $sport_arena->latitude }}, {{ $sport_arena->longitude }}], {{ config('leaflet.detail_zoom_level') }});

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([{{ $sport_arena->latitude }}, {{ $sport_arena->longitude }}]).addTo(map)
        .bindPopup('{!! $sport_arena->map_popup_content !!}');
</script>
@endpush
