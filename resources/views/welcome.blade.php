@extends('layouts.layout')

@section('title', 'Feedback Form')
<style>
    @media (max-width: 767px) {

        /* Override box-sizing for mobile screens */
        .container {
            box-sizing: content-box;
            padding: 0 !important;
        }
    }
</style>
@section('content')
    <div class="container" style="padding: 80px">
        @if (Auth::user() && Auth::user()->role_id == 2)
            <!-- Button to trigger the modal -->

            <div class="d-flex justify-content-end mb-3">
                @if (auth()->user()->hasDirectPermission('posting'))
                    <button type="button" class="btn btn-success ml-auto add_feedback_btn" data-toggle="modal"
                        data-target="#feedbackModal">
                        Add Feedback
                    </button>
                @else
                    <p class=" btn btn-success disabled" title="blocked by admin">Add Feedback</p>
                @endif
            </div>
            <hr>

            <!-- Modal -->
            <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="feedbackModalLabel">Feedback Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" id="feedbacks-form" method="post">
                                @csrf

                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                    <div class="user-suggestions"></div>
                                </div>

                                <div class="form-group">
                                    <label for="category">Category:</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="bug">Bug</option>
                                        <option value="feature">Feature Request</option>
                                        <option value="improvement">Improvement</option>
                                    </select>
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            @if ($feedbacks->isEmpty())
                <h2 class="bg-light w-75">Nothing posted yet !</h2>
            @else
                @foreach ($feedbacks as $index => $feedback)
                    <div class="col-md-12 mb-2">

                        <div class="post-container mb-4">
                            <div class="post-header bg-primary text-white p-3">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="m-0">{{ $feedback->user->name }}</h5>
                                        <small>{{ $feedback->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="post-content p-3">
                                <h4 class="mb-3">{{ $feedback->title }}</h4>
                                <p>{{ $feedback->description }}</p>
                                <p class="text-muted">Category: <strong>{{ $feedback->category }}</strong></p>
                            </div>
                            <div class="post-footer bg-light p-3">
                                @auth
                                    <span class="position-relative vote-toggle" data-feedback-id="{{ $feedback->id }}"
                                        data-voted="{{ $feedback->vote && $feedback->vote->vote_count == '1' ? 'false' : 'true' }}"
                                        style="cursor: pointer">
                                        <b class="m-2">Vote</b>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="{{ $feedback->vote && $feedback->vote->vote_count == '1' ? 'red' : 'currentColor' }}"
                                            class="bi bi-heart" viewBox="0 0 16 16">
                                            <path
                                                d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z" />
                                        </svg>
                                    </span>
                                @endauth
                                <p class="font-weight-bold ">Total Votes:<span class="total_votes">
                                        {{ count($feedback->votes) }} </span></p>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="mt-3 feedbacks_comments">
                            <h5>Comments</h5>
                            @if ($feedback->comments != null)
                                @foreach ($feedback->comments as $comment)
                                    <div class="media mb-3">
                                        <div class="rounded-circle profile-circle text-white text-center mr-2"
                                            style="width: 40px; height: 40px; line-height: 40px; background-color: {{ getUserColor($comment->user_encrypted_id) }}">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mt-0">{{ $comment->user->name }}</h6>
                                            <p>{!! $comment->comment_text !!}</p>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>

                                    </div>
                                @endforeach
                            @endif

                        </div>

                        <!-- Comment Input Section -->
                        @if (Auth::user() && Auth::user()->role_id == 2)
                            <div class="mt-3 col-md-12">
                                <h5>Post a Comment</h5>
                                @if (auth()->user()->hasDirectPermission('commenting'))
                                    <form action="" id="post-comment-{{ $index }}" method="post">
                                        <div class="mb-3">
                                            <input type="hidden" name="post_id"
                                                value="{{ $feedback->encrypted_id }}"></span>
                                            <label for="commentInput" class="form-label">Your Comment:</label>
                                            <textarea class="form-control summernote" id="summernote-{{ $index }}" name="comments_input" rows="3"></textarea>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Post Comment</button>
                                    </form>
                                @else
                                    <span class="text-danger">You have been blocked by admin to not comment</span>
                                @endif
                            </div>
                        @endif
                        <hr>

                    </div>
                @endforeach
                {{ $feedbacks->links() }}

            @endif
        </div>
    @endsection

    @section('scripts')
        <script>
            <?php
            function getUserColor($userId)
            {
                $color = '#' . substr($userId, 0, 6);
                return $color;
            }
            ?>



            @if (session('success'))

                toastr.success(
                    'Success!',
                    '{{ session('success') }}', {
                        positionClass: 'toast-bottom-right'
                    }
                );
            @endif

            $(document).ready(function() {
                $('.vote-toggle').on('click', function() {
                    var feedbackId = $(this).data('feedback-id');
                    var hasVoted = $(this).data('voted');
                    console.log(hasVoted);
                    if (hasVoted == 'false' || hasVoted == false) {
                        console.log('ok');
                        $(this).find('.bi-heart').attr('fill', 'current');
                        $(this).data('voted', 'true');
                        $(this).addClass('voted');
                        let totalVotesElement = $(this).siblings('p').find('.total_votes');

                        // Assuming .total_votes contains a numeric value, convert it to a number
                        let currentTotalVote = parseInt(totalVotesElement.html());
                        // Increment the vote count
                        totalVotesElement.text(currentTotalVote - 1);

                    } else {
                        console.log(hasVoted);
                        $(this).find('.bi-heart').attr('fill', 'red');
                        $(this).data('voted', 'false');
                        $(this).removeClass('voted');
                        let totalVotesElement = $(this).siblings('p').find('.total_votes');


                        // Assuming .total_votes contains a numeric value, convert it to a number
                        let currentTotalVote = parseInt(totalVotesElement.html());
                        // Increment the vote count
                        totalVotesElement.text(currentTotalVote + 1);

                    }
                    $.ajax({
                        method: "POST",
                        url: "{{ route('add-vote') }}",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'voted': hasVoted,
                            'feedbackId': feedbackId
                        },
                        success: function(data) {

                        },

                    });
                });
                $('.add_feedback_btn').on('click', function() {
                    $('#feedbackModal').modal('show');
                })
                $('.pagination a').each(function() {
                    var originalHref = $(this).attr('href');

                    // Check if the href already contains the desired base URL
                    if (!originalHref.includes('localhost/ProductFeedbacks')) {
                        var updatedHref = '/ProductFeedbacks' + originalHref; // Update the URL as needed
                        $(this).attr('href', updatedHref);
                    }
                });
                $('.summernote').summernote({
                    height: 50,
                    minHeight: null,
                    maxHeight: null,
                    focus: true
                }).on('summernote.change', function() {
                    var text = $(this).val();
                    var mentions = extractMentions(text);
                    console.log(mentions.length);
                    if (mentions.length > 0) {
                        fetchUserSuggestions(mentions);
                    }
                });
                $('form[id^="post-comment"]').on('submit', function(e) {
                    $('.form-errors').remove();
                    $('.border-danger').removeClass('border-danger');
                    e.preventDefault();

                    // Extract index from form ID
                    var index = $(this).attr('id').split('-')[2];

                    let formData = new FormData(this);
                    $.ajax({
                        method: "POST",
                        url: "{{ route('post-comment') }}",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: formData,
                        success: function(data) {
                            if (data.success) {
                                // Clear existing comments for the specific feedback
                                $('.feedbacks_comments').eq(index).html('');

                                // Append new comments
                                data.comments.forEach(function(comment) {
                                    console.log(comment.user_encrypted_id);
                                    var commentHtml = '<div class="media mb-3">' +
                                        '<div class="rounded-circle profile-circle bg-secondary text-white text-center mr-2" ' +
                                        'style="width: 40px; height: 40px; line-height: 40px; background-color: ' +
                                        getUserColor(comment.user_encrypted_id) +
                                        '!important;">' +
                                        comment.user.name.substring(0, 1) +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<h6 class="mt-0">' + comment.user.name +
                                        '</h6>' +
                                        '<p>' + comment.comment_text + '</p>' +
                                        '<small class="text-muted">' + getHumanReadableTime(
                                            comment.created_at) + '</small>' +
                                        '</div>' +
                                        '</div>';

                                    function getUserColor(userId) {
                                        return '#' + userId.substring(0, 6);
                                    }




                                    function getHumanReadableTime(created_at) {
                                        // Assuming created_at is in ISO format, e.g., "2023-11-05T12:34:56Z"
                                        var momentTime = moment(created_at);
                                        return momentTime.fromNow();
                                    }

                                    $('.feedbacks_comments').eq(index).append(commentHtml);
                                });


                                toastr.success(
                                    'Success!',
                                    data.message, {
                                        positionClass: 'toast-bottom-right'
                                    }
                                );
                            }
                        },
                        error: function(response) {
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;

                                $.each(errors, function(field, messages) {
                                    var input = $('[name="' + field + '"]');
                                    $('#post-comment-' + index).find(input).addClass(
                                        'border-danger')
                                    var errorList = $(
                                        '<ul class="text-danger form-errors"></ul>');
                                    $.each(messages, function(index, message) {
                                        errorList.append($('<li></li>').text(
                                            message));
                                    });
                                    $('#post-comment-' + index).find(input).closest(
                                            '.form-group')
                                        .append(
                                            errorList);
                                });
                            }
                        }
                    });
                });


                function extractMentions(text) {
                    // Regular expression to find mentions (usernames starting with @)
                    var mentionRegex = /@(\w+)/g;
                    var mentions = [];
                    var match;

                    while ((match = mentionRegex.exec(text)) !== null) {
                        mentions.push(match[1]);
                    }

                    return mentions;
                }

                function fetchUserSuggestions(mentions) {
                    console.log(mentions, 'kkd');
                    $.ajax({
                        method: 'post',
                        dataType: "json",
                        url: "{{ route('user-suggestions') }}",
                        data: {
                            mentions: mentions
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            // Update UI to display user suggestions
                            displayUserSuggestions(data.users);
                        },
                        error: function(error) {
                            console.error('Error fetching user suggestions:', error);
                        }
                    });
                }

                function displayUserSuggestions(users) {
                    // Clear previous suggestions
                    $('.user-suggestions').empty();

                    // Create a select element
                    var selectElement = $(
                        '<select class="selectpicker" data-live-search="true" data-style="btn-info"></select>');

                    // Add an option for each user
                    users.forEach(function(user) {
                        var option = $('<option></option>').text(user.username);
                        selectElement.append(option);
                    });

                    // Append the select element to the container
                    $('.user-suggestions').append(selectElement);

                    // Initialize the Bootstrap SelectPicker
                    $('.selectpicker').selectpicker('refresh');

                    // Attach an event listener for when the user chooses an option
                    $('.selectpicker').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                        // Implement logic to handle the selected user
                        var selectedUser = users[clickedIndex];
                        console.log('Selected User:', selectedUser);

                        // Clear user suggestions
                        $('.user-suggestions').empty();
                    });
                }

            });

            $('#feedbacks-form').on('submit', function(e) {
                $('.form-errors').remove();
                $('.border-danger').removeClass('border-danger');
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    method: "POST",
                    url: "{{ route('add-feedbacks') }}",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData,
                    success: function(data) {
                        if (data.success) {

                            toastr.success(
                                'Success!',
                                data.message, {
                                    positionClass: 'toast-bottom-right'
                                }

                            );
                            location.reload();
                            // setTimeout(() => {
                            // window.location.href = "{{ url('/login') }}";

                            // }, 2000);

                        }
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;

                            $.each(errors, function(field, messages) {
                                var input = $('[name="' + field + '"]');
                                $('#feedbacks-form').find(input).addClass('border-danger')
                                var errorList = $(
                                    '<ul class="text-danger form-errors"></ul>');
                                $.each(messages, function(index, message) {
                                    errorList.append($('<li></li>').text(
                                        message));
                                });
                                $('#feedbacks-form').find(input).closest('.form-group').append(
                                    errorList);
                            });
                        }
                    }
                });
            });
        </script>

    @endsection
