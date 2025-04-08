<h5>Kelola Kategori</h5>

<form id="category-form">
    <input type="text" id="new-category-name" class="form-control" placeholder="Tambah kategori">
    <button type="submit" class="btn btn-primary mt-2">Tambah</button>
</form>

<ul id="category-list" class="list-group mt-3">
    <!-- kategori akan dimuat di sini -->
</ul>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('category-list');
    const form = document.getElementById('category-form');
    const input = document.getElementById('new-category-name');

    // Muat kategori
    function loadCategories() {
        fetch('/category')
            .then(res => res.json())
            .then(data => {
                list.innerHTML = '';
                data.forEach(category => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.innerHTML = `
                        ${category.name}
                        <button class="btn btn-sm btn-danger" onclick="deleteCategory(${category.id})">Hapus</button>
                    `;
                    list.appendChild(li);
                });
            });
    }

    // Tambah kategori
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        fetch('/category', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                name: input.value
            })
        }).then(() => {
            input.value = '';
            loadCategories();
        });
    });

    // Hapus kategori
    window.deleteCategory = function (id) {
        if (confirm('Yakin mau hapus kategori ini?')) {
            fetch(`/category/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => loadCategories());
        }
    };

    loadCategories();
});
</script>
