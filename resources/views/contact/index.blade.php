@extends('adminlte::page')

@section('title', 'Contact Messages')

@section('content')
<div class="container mt-4">
    <h2>Contact Messages</h2>

    <table class="table table-bordered" id="contactTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Organisation</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

<!-- View Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Contact Message Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Name:</strong> <span id="modalName"></span></p>
        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
        <p><strong>Organisation:</strong> <span id="modalOrganisation"></span></p>
        <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
        <p><strong>Website/Social:</strong> <span id="modalWebsite"></span></p>
        <p><strong>Submitted At:</strong> <span id="modalDate"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="deleteBtn">Delete</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- DataTables & Bootstrap Dependencies -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
let selectedId = null;

$(document).ready(function () {
    const table = $('#contactTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.contact.data') }}',
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'organisation_name' },
            { data: 'created_at' },
            {
                data: 'id',
                render: function(data, type, row) {
                    return `<button class="btn btn-primary btn-sm viewBtn" data-id="${data}">View</button>`;
                },
                orderable: false
            }
        ]
    });

    // View message
    $(document).on('click', '.viewBtn', function () {
        selectedId = $(this).data('id');
        $.get(`/admin/contact-messages/${selectedId}`, function(data) {
            $('#modalName').text(data.name);
            $('#modalEmail').text(data.email);
            $('#modalOrganisation').text(data.organisation_name || '-');
            $('#modalPhone').text(data.phone_number || '-');
            $('#modalWebsite').text(data.website_or_social_link || '-');
            $('#modalDate').text(new Date(data.created_at).toLocaleString());
            $('#contactModal').modal('show');
        });
    });

    // Delete message
    $('#deleteBtn').click(function () {
        if (confirm('Are you sure you want to delete this message?')) {
            $.ajax({
                url: `/admin/contact-messages/${selectedId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#contactModal').modal('hide');
                    table.ajax.reload();
                    alert(response.message);
                },
                error: function() {
                    alert('Error deleting message.');
                }
            });
        }
    });
});
</script>
@endpush
