@extends('layout.skeleton')

@section('title', $sport_arena->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4><b>Periksa Pemesanan Anda</b></h4>
                <p>Pastikan detail pemesanan sudah sesuai dan benar.</p>
            </div>
            <form id="book-form" action="{{ route('booking.confirmation.action', $sport_arena->id) }}" method="post">
                @csrf
                <div class="card-body">
                    @foreach (Cache::get('cart') as $cart)
                        @php
                            $schedule = $schedules->where('id', $cart['schedule_id'])->first();
                        @endphp
                        <div class="data-booking active" data-value="{{ $schedule->id }}" data-date="{{ $cart['date'] }}">
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <h5>{{ $sport_arena->name }}</h5>
                                    <span>{{ date("d M Y", strtotime($cart['date'])) }}</span>
                                    <span>{{ date("H:i", strtotime($schedule->time_start)) }} - {{ date("H:i", strtotime($schedule->time_end)) }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="float-sm-right">
                                    Rp. <h3>{{ number_format($schedule->price) }}</h3>
                                    <button type="button" class="btn btn-xs btn-danger remove-booking" onclick=removeBooking('{{ $schedule->id }}')><i class="fas fa-trash"></i></button>
                                    </span>
                                </div>
                            </div>
                            <hr>
                            <input type="hidden" name="dates[]" value="{{ $cart['date'] }}">
                            <input type="hidden" name="schedule_ids[]" value="{{ $schedule->id }}">
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <button id="book-btn" type="submit" class="btn btn-block bg-primary" style="color: white; font-weight: bold;">
                        Book Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
        let getData = [];

        function getAllBookingId()
        {
            getData = [];

            $(".data-booking").each(function() {            
                if ($(this).hasClass('active')) {
                    var id = $(this).attr("data-value");
                    var date = $(this).attr("data-date");
                    getData.push({'schedule_id': id, 'date': date});
                }
            });
        }
        
        function removeBooking(id)
        {
            $('div.data-booking').filter(function() {
                $('[data-value='+id+']').removeClass('active');
                var d = $(this).data('value');
                return d == id;
            }).hide();

            getAllBookingId();
            updateBookId();
        }

        function updateBookId()
        {
            $.ajax({
				url: "{{ route('booking.add-cart') }}",
				method: "GET",
				dataType: "HTML",
				data: {'data':JSON.stringify(getData)},
				success: function (response) {
                    console.log(response)
				},
				error: function () {

				}
			});
        }
    </script>
@endpush