@extends('layouts.app')

@section('title', 'All Users')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users"></i> All Users
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.pending') }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-user-clock"></i> Pending Approvals
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

                @if($users->count() == 0)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No users registered yet.
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
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="{{ !$user->is_approved ? 'table-warning' : '' }}">
                                    <td>
                                        <i class="fas fa-user"></i>
                                        <strong>{{ $user->name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                    <td>
                                        @if($user->role == 'buyer')
                                            <span class="badge bg-info">
                                                <i class="fas fa-shopping-cart"></i> Buyer
                                            </span>
                                        @elseif($user->role == 'seller')
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
                                        @if($user->is_approved)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Approved
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $user->created_at->format('M d, Y') }}</small><br>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.toggle', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @if($user->is_approved)
                                                <button type="submit" class="btn btn-sm btn-warning"
                                                        onclick="return confirm('Suspend this user?')"
                                                        title="Suspend User">
                                                    <i class="fas fa-ban"></i> Suspend
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-success"
                                                        title="Activate User">
                                                    <i class="fas fa-check"></i> Activate
                                                </button>
                                            @endif
                                        </form>
                                        <form action="{{ route('admin.reject', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Permanently delete this user?')"
                                                    title="Delete User">
                                                <i class="fas fa-trash"></i> Delete
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
