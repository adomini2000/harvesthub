@extends('layouts.app')

@section('title', 'Pending User Approvals')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-user-clock"></i> Pending User Approvals
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-users"></i> All Users
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- Success Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Error Messages --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($pendingUsers->count() == 0)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No pending approvals at this time.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Details</th>
                                    <th>Registered</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingUsers as $user)
                                <tr>
                                    <td>
                                        <i class="fas fa-user"></i>
                                        <strong>{{ $user->name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                    <td>
                                        @if($user->role == 'seller')
                                            <span class="badge bg-success">
                                                <i class="fas fa-store"></i> Seller
                                            </span>
                                        @elseif($user->role == 'rider')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-motorcycle"></i> Rider
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->role == 'seller' && $user->seller)
                                            <small>
                                                <strong>Shop:</strong> {{ $user->seller->shop_name }}<br>
                                                <strong>Desc:</strong> {{ Str::limit($user->seller->shop_description ?? 'N/A', 30) }}
                                            </small>
                                        @elseif($user->role == 'rider' && $user->rider)
                                            <small>
                                                <strong>Vehicle:</strong> {{ ucfirst($user->rider->vehicle_type) }}<br>
                                                <strong>Capacity:</strong> {{ $user->rider->max_capacity_kg }}kg
                                            </small>
                                        @else
                                            <small class="text-muted">No details</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $user->created_at->format('M d, Y') }}</small><br>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.approve', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reject', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to reject and delete this user?')"
                                                    title="Reject & Delete">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
