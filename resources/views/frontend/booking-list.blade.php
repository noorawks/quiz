@extends('layout.skeleton')

@section('title', 'My Booking List')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4><b>Periksa Pemesanan Anda</b></h4>
                <p>Pastikan detail pemesanan sudah sesuai dan benar.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="" accept-charset="UTF-8" class="form-inline">
                    <div class="form-group">
                        <label for="q" class="control-label">Search</label>
                        <input placeholder="Search" name="q" type="text" id="q" class="form-control mx-sm-2" value="{{ request('q') }}">
                    </div>
                    <input type="submit" value="Search" class="btn btn-secondary">
                </form>
            </div>
            <table class="table table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">Date Transaction</th>
                        <th>Booking Number</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="text-center">Payment Proof</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $key => $booking)
                    <tr>
                        <td class="text-center">{{ $booking->created_at }}</td>
                        <td>#{{ $booking->booking_number }}</td>
                        <td>Rp. {{ number_format($booking->amount) }},-</td>
                        <td>
                            @if ($booking->isPending)
                                <span class="pr-1 pl-1" style="background-color:grey; color:white; font-weight: bold;">Pending</span>
                            @elseif ($booking->isPaid)
                                <span class="pr-1 pl-1" style="background-color:#90EE90; color:white; font-weight: bold;">Paid</span>
                            @elseif ($booking->isExpired)
                                <span class="pr-1 pl-1" style="background-color:red; color:white; font-weight: bold;">Expired</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if (!empty($booking->payment_proof))
                                <a class="btn btn-sm btn-secondary" href="{{ $booking->payment_proof }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($booking->isPending)
                                <button onclick="uploadPaymentProof('{{ $booking->id }}')" class="btn btn-xs btn-primary"><i class="fa fa-upload" aria-hidden="true"></i> Upload Payment</button>
                            @else 
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $bookings->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    <div class="modal fade" id="upload-payment-proof">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Payment Proof</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="upload_payment_proof" class="form-horizontal" autocomplete="off" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input name="payment_proof" type="file">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function uploadPaymentProof(booking_id) {
            let actionUrl = '{{ route("booking.upload-payment-proof", ":booking_id") }}';
            actionUrl = actionUrl.replace(':booking_id', booking_id);
            $('#upload_payment_proof').attr('action', actionUrl);
            $("#upload-payment-proof").modal('show');
        }
    </script>
@endpush