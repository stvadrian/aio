<form action="" method="post">
    @csrf
    <input type="hidden" name="delete">
    {{ $deleteBody }}
    <button type="button" name="delete" class="btn {{ isset($classBtn) ? $classBtn : 'btn-danger' }} btn-sm btn-delete"
        data-name="{{ $deleteName ? $deleteName : 'this item' }}">
        <i class="ti ti-trash fs-4"></i>
    </button>
</form>
