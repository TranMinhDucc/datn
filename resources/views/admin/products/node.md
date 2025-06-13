@endsection

@section('js')
<script>
    document.getElementById('product-form').addEventListener('submit', function(e) {
        const variantList = document.getElementById('manual-variant-list');

        if (!variantList || variantList.children.length === 0) {
            e.preventDefault(); // Ngăn form submit
            Swal.fire({
                icon: 'warning',
                title: 'Thiếu biến thể',
                text: 'Vui lòng thêm ít nhất một biến thể sản phẩm trước khi lưu.',
                confirmButtonText: 'OK'
            });
        }
    });


    const attributes = @json($attributes);
    let variantIndex = 0;

    function slugify(str) {
        return str.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    function generateSKU(productName, selectedValues) {
        const slug = slugify(productName).toUpperCase();
        const valueStr = selectedValues.map(val => val.toUpperCase()).join('-');
        return slug + '-' + valueStr;
    }
////////////////////////////
let attributeGroupIndex = 0;
let attributeGroups = {};

function addAttributeGroup() {
    const container = document.getElementById('attribute-group-list');

    const groupId = `attribute_group_${attributeGroupIndex}`;
    attributeGroups[groupId] = [];

    const div = document.createElement('div');
    div.classList.add('mb-3');
    div.id = groupId;

    div.innerHTML = `
        <div class="d-flex align-items-center gap-2 mb-2">
            <input type="text" class="form-control w-25" placeholder="Tên nhóm phân loại" oninput="updateAttributeGroups()" />
            <button type="button" class="btn btn-sm btn-danger" onclick="removeAttributeGroup('${groupId}')">Xoá</button>
        </div>
        <div class="input-group mb-2">
            <input type="text" class="form-control" placeholder="Tuỳ chọn (VD: Đỏ, Xanh)" onkeydown="handleAttributeEnter(event, '${groupId}')">
            <button type="button" class="btn btn-outline-secondary" onclick="updateAttributeGroups()">Cập nhật</button>
        </div>
        <div class="d-flex flex-wrap gap-2" id="${groupId}_tags"></div>
        <hr>
    `;

    container.appendChild(div);
    attributeGroupIndex++;
}

function removeAttributeGroup(id) {
    delete attributeGroups[id];
    document.getElementById(id)?.remove();
    generateCombinations();
}

function handleAttributeEnter(e, groupId) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const value = e.target.value.trim();
        if (!value) return;

        if (!attributeGroups[groupId].includes(value)) {
            attributeGroups[groupId].push(value);
            renderAttributeTags(groupId);
            generateCombinations();
        }

        e.target.value = '';
    }
}

function renderAttributeTags(groupId) {
    const container = document.getElementById(`${groupId}_tags`);
    container.innerHTML = '';

    attributeGroups[groupId].forEach(val => {
        const badge = document.createElement('span');
        badge.className = 'badge bg-primary text-white p-2 rounded';
        badge.innerHTML = `${val} <span class="ms-2 cursor-pointer text-danger" onclick="removeTag('${groupId}', '${val}')">&times;</span>`;
        container.appendChild(badge);
    });
}

function removeTag(groupId, value) {
    attributeGroups[groupId] = attributeGroups[groupId].filter(v => v !== value);
    renderAttributeTags(groupId);
    generateCombinations();
}

function updateAttributeGroups() {
    generateCombinations();
}

function generateCombinations() {
    const groupNames = Object.keys(attributeGroups);
    const valueLists = groupNames.map(id => attributeGroups[id]);

    if (valueLists.some(list => list.length === 0)) {
        document.getElementById('variant-list').innerHTML = '';
        return;
    }

    const combinations = cartesian(valueLists);
    const tbody = document.getElementById('variant-list');
    tbody.innerHTML = '';

    combinations.forEach((combo, i) => {
        const label = combo.join(' / ');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${label}<input type="hidden" name="variants[${i}][attributes]" value="${label}"></td>
            <td><input type="number" name="variants[${i}][price]" class="form-control" required></td>
            <td><input type="number" name="variants[${i}][quantity]" class="form-control" required></td>
            <td><input type="text" name="variants[${i}][sku]" class="form-control"></td>
            <td><input type="file" name="variants[${i}][image]" class="form-control"></td>
        `;
        tbody.appendChild(row);
    });
}

// Cartesian product utility
function cartesian(arrays) {
    return arrays.reduce((acc, curr) => {
        return acc.flatMap(a => curr.map(b => a.concat(b)));
    }, [[]]);
}
////////////////////////////////
    let previewId = 0;

    document.getElementById('image-input').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const previewContainer = document.getElementById('image-preview-container');

        previewContainer.innerHTML = ''; // Reset ảnh cũ nếu chọn lại

        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const imgWrapper = document.createElement('div');
                imgWrapper.className = 'position-relative';
                imgWrapper.style.width = '100px';

                const img = document.createElement('img');
                img.src = event.target.result;
                img.className = 'rounded border';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.width = '100%';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 m-1';
                removeBtn.innerHTML = '&times;';
                removeBtn.onclick = function() {
                    // ❌ Không xóa khỏi mảng vì không dùng FormData custom
                    imgWrapper.remove();
                    // 👇 Trick: xóa ảnh khỏi input bằng cách clone lại input
                    const input = document.getElementById('image-input');
                    const dt = new DataTransfer();
                    Array.from(input.files).forEach((f, i) => {
                        if (i !== index) dt.items.add(f);
                    });
                    input.files = dt.files;
                };

                imgWrapper.appendChild(img);
                imgWrapper.appendChild(removeBtn);
                previewContainer.appendChild(imgWrapper);
            };
            reader.readAsDataURL(file);
        });
    });



    function slugify(str) {
        return str.toString().toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // loại dấu tiếng Việt
            .replace(/[^a-z0-9 -]/g, '') // chỉ giữ chữ cái, số, dấu cách và gạch
            .replace(/\s+/g, '-') // chuyển dấu cách thành -
            .replace(/-+/g, '-') // loại bớt dấu -
            .replace(/^-+|-+$/g, ''); // loại - ở đầu/cuối
    }

    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('product-name');
        const slugInput = document.getElementById('product-slug');

        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                slugInput.value = slugify(nameInput.value);
            });
        }
    });
</script>


@endsection