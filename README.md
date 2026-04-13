# 📘 HƯỚNG DẪN CHẠY PROJECT RECHECK EXAM

## 🚀 1. Clone project

```bash
git clone https://github.com/HyLe2302/back-end-web-exam.git
cd back-end
```

---

## 📦 2. Cài đặt thư viện (vendor)

```bash
composer install
```

---

## ⚙️ 3. Tạo file môi trường `.env`

Tạo file `.env` trong thư mục gốc project và thêm nội dung:

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=recheck_exam
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🗄️ 4. Import database

* Mở phpMyAdmin hoặc MySQL
* Tạo database: `recheck_exam`
* Import file:

```bash
recheck_exam.sql
```

---

## ▶️ 5. Chạy server

Nếu dùng PHP built-in server:

```bash
php -S localhost:8000 -t public
```

---

## 🌐 6. Truy cập hệ thống

Mở trình duyệt:

```
http://localhost:8000
```

## 👨‍💻 Tác giả

* HyLe2302

