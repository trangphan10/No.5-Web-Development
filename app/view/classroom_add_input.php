<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="E:\\Dai hoc\\HK7\\LTWeb\\web\\style.css">
    </head>
    <body>
    <div class="form-container">
        <form>
            <div class="form-group">
                <label for="fullname">Họ và Tên</label>
                <input type="text" id="fullname" name="fullname" />
            </div>
            
            <div class="form-group">
                <label>Phân loại</label>
                <div class="radio-group">
                    <label><input type="radio" name="category" value="Giáo viên" /> Giáo viên</label>
                    <label><input type="radio" name="category" value="Sinh viên" /> Sinh viên</label>
                    <label><input type="radio" name="category" value="Cựu sinh viên" /> Cựu sinh viên</label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" id="id" name="id" />
            </div>
            
            <div class="form-group">
                <label for="avatar">Avatar</label>
                <input type="file" id="avatar" name="avatar" />
            </div>
            
            <div class="form-group">
                <label for="description">Mô tả thêm</label>
                <textarea id="description" name="description"></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="submit-btn">Xác Nhận</button>
            </div>
        </form>
    </div> 

    </body>
</html>