# Hướng dẫn sử dụng Docker cho dự án Fastship-HU

## Cài đặt

1. Đảm bảo đã cài đặt Docker và Docker Compose trên máy của bạn.
2. Clone dự án về máy của bạn.
3. Copy file `.env.example` thành `.env` và cập nhật các thông số kết nối:

```bash
cp .env.example .env
```

4. Cập nhật các thông số sau trong file `.env`:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=root
```

## Khởi động dự án

1. Build và khởi động các container:

```bash
docker-compose up -d
```

2. Vào container app để cài đặt dependencies:

```bash
docker-compose exec app composer install
```

3. Tạo key cho ứng dụng:

```bash
docker-compose exec app php artisan key:generate
```

4. Chạy migrations:

```bash
docker-compose exec app php artisan migrate
```

5. Tạo symbolic link cho storage:

```bash
docker-compose exec app php artisan storage:link
```

## Thao tác thường dùng

### Khởi động dự án:

```bash
docker-compose up -d
```

### Dừng dự án:

```bash
docker-compose down
```

### Truy cập vào container:

```bash
docker compose exec app bash
```

### Chạy lệnh artisan:

```bash
docker-compose exec app php artisan [command]
```

### Xem logs:

```bash
docker-compose logs -f
```

## Truy cập ứng dụng

Sau khi khởi động, ứng dụng sẽ chạy tại địa chỉ: [http://localhost:8000](http://localhost:8000) 