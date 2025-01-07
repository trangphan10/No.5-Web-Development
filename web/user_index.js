function validateInfo(event) {
    event.preventDefault(); // Ngăn chặn form submit mặc định

    // Xóa các thông báo lỗi trước đó
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

    // Lấy giá trị các trường
    var fullname = document.getElementById("fullname").value.trim();
    var id = document.getElementById("id").value.trim();
    var checkAvatar = document.getElementById("avatar");
    var description = document.getElementById("description").value.trim();
    var selectedRadio = document.querySelector('input[name="category"]:checked');

    let hasError = false;

    // Kiểm tra họ và tên
    if (fullname.length === 0) {
        document.getElementById("fullname-error").textContent = "Họ và tên không được để trống.";
        hasError = true;
    }

    // Kiểm tra phân loại
    if (!selectedRadio) {
        document.getElementById("category-error").textContent = "Vui lòng chọn phân loại.";
        hasError = true;
    }

    // Kiểm tra ID
    if (id.length === 0) {
        document.getElementById("id-error").textContent = "ID không được để trống.";
        hasError = true;
    }

    // Kiểm tra avatar
    if (checkAvatar.files.length === 0) {
        document.getElementById("avatar-error").textContent = "Vui lòng chọn ảnh đại diện.";
        hasError = true;
    }

    // Kiểm tra mô tả
    if (description.length === 0) {
        document.getElementById("description-error").textContent = "Mô tả không được để trống.";
        hasError = true;
    }

    // Nếu có lỗi, dừng xử lý tiếp
    if (hasError) {
        return;
    }

    // Nếu không có lỗi, tiếp tục gửi dữ liệu
    const formData = new FormData();
    formData.append('fullname', fullname);
    formData.append('id', id);
    formData.append('description', description);
    formData.append('avatar', checkAvatar.files[0]);
    formData.append('category', selectedRadio.value);

    fetch('/../project_main/app/view/user_add/user_add_confirm.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            return response.text();
        }
        throw new Error('Network response was not ok.');
    })
    .then(data => {
        document.body.innerHTML = data;
    })
    .catch(error => console.error('Error:', error));
}