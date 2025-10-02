<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Company</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <h2>Company form</h2>
        <div id="error-messages" class="alert alert-danger d-none"></div>
        <div id="success-message" class="alert alert-success d-none"></div>
        <form id="company-form" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 mt-3">
                <label for="name">Company Name:</label>
                <input type="text" class="form-control" id="name" placeholder="Enter Company Name"
                    name="name">
                <div class="text-danger" id="name-error"></div>
            </div>
            <div class="mb-3">
                <label>Excel file</label>
                <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls">
                <div class="text-danger" id="file-error"></div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#company-form').on('submit', function(e) {
                e.preventDefault();

                // Clear previous errors
                $('.text-danger').text('');
                $('#error-messages').addClass('d-none');
                $('#success-message').addClass('d-none');

                // Create FormData
                const formData = new FormData(this);

                // Submit via AJAX
                $.ajax({
                    url: '{{ route('stocks.import') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#success-message').text(response.msg).removeClass('d-none');
                        } else {
                            $('#error-message').text(response.msg).removeClass('d-none');
                        }

                        $('#company-form')[0].reset();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorHtml = '<ul>';
                            $.each(errors, function(key, value) {
                                errorHtml += '<li>' + value[0] + '</li>';
                                $('#' + key + '-error').text(value[0]);
                            });
                            errorHtml += '</ul>';
                            $('#error-messages').html(errorHtml).removeClass('d-none');
                        } else {
                            $('#error-messages').text('An error occurred. Please try again.')
                                .removeClass('d-none');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
