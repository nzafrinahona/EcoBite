@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white p-4 text-center border-0">
                    <h3 class="mb-0 fw-bold">Pickup Verification</h3>
                    <p class="text-secondary small mb-0">Staff Terminal • EcoBite</p>
                </div>
                <div class="card-body p-5">
                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-3 mb-4 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-3 mb-4 d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('reservations.verify.process') }}" method="POST">
                        @csrf
                        <div class="mb-4 text-center">
                            <label for="pickup_code" class="form-label text-uppercase letter-spacing-1 fw-semibold text-secondary small mb-3">Enter 5-Digit Pickup Code</label>
                            <input type="text" 
                                   name="pickup_code" 
                                   id="pickup_code" 
                                   class="form-control form-control-lg text-center fw-bold fs-2 tracking-widest border-2 @error('pickup_code') is-invalid @enderror" 
                                   placeholder="XXXXX" 
                                   maxlength="5" 
                                   required 
                                   style="text-transform: uppercase; letter-spacing: 0.5rem;"
                                   autofocus>
                            @error('pickup_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100 py-3 rounded-3 fw-bold text-uppercase shadow-sm">
                            Verify & Complete Pickup
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light p-3 text-center border-0">
                    <span class="text-muted small">Please verify student ID before confirming.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .letter-spacing-1 { letter-spacing: 1px; }
    .tracking-widest { letter-spacing: 0.5rem; }
    .form-control:focus {
        border-color: #212529;
        box-shadow: 0 0 0 0.25rem rgba(33, 37, 41, 0.1);
    }
</style>
@endsection
