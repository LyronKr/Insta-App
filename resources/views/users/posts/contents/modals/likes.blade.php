<div class="modal fade" id="like-modal-{{ $post->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-primary">
            <div class="modal-header border-primary">
                <h2 class="h4 fw-bold text-primary text-center">Users who liked this post</h2>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center px-2">
                    <div class="col-12">
                        @foreach ($post->likes as $like)
                            <div class="row align-items-center mb-3">
                                <div class="col-auto">
                                    <a href="{{ route('profile.show', $like->user->id) }}">
                                        @if ($like->user->avatar)
                                            <img src="{{ $like->user->avatar }}" alt="{{ $like->user->name }}" class="rounded-circle avatar-md">
                                        @else
                                            <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                                        @endif
                                    </a>
                                </div>
                                <div class="col ps-0 text-truncate">
                                    <a href="{{ route('profile.show', $like->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $like->user->name }}</a>
                                    <p class="text-muted mb-0">{{ $like->user->email }}</p>
                                </div>
                                <div class="col-auto">
                                    @if ($like->user->id !== Auth::user()->id)
                                        @if ($like->user->isFollowed())
                                            <form action="{{ route('follow.destroy', $like->user->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-secondary fw-bold btn-sm">Following</button>
                                            </form>
                                        @else
                                            <form action="{{ route('follow.store', $like->user->id) }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-primary fw-bold btn-sm">Follow</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>                    
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>