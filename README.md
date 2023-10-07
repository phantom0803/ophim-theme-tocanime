# THEME - TOC ANIME 2023 - OPHIM CMS

## Demo
### Trang Chủ
![Alt text](https://i.ibb.co/f9XNtG4/TOCANIME-INDEX.png "Home Page")

### Trang Danh Sách Phim
![Alt text](https://i.ibb.co/r006ZBn/TOCANIME-CATALOG.png "Catalog Page")

### Trang Thông Tin Phim
![Alt text](https://i.ibb.co/F7HMSnH/TOCANIME-SINGLE.png "Single Page")

### Trang Xem Phim
![Alt text](https://i.ibb.co/jGJh7Z2/TOCANIME-EPISODE.png "Episode Page")

## Requirements
https://github.com/hacoidev/ophim-core

## Install
1. Tại thư mục của Project: `composer require ophimcms/theme-tocanime`
2. Kích hoạt giao diện trong Admin Panel

## Update
1. Tại thư mục của Project: `composer update ophimcms/theme-tocanime`
2. Re-Activate giao diện trong Admin Panel

## Note
- Một vài lưu ý quan trọng của các nút chức năng:
    + `Activate` và `Re-Activate` sẽ publish toàn bộ file js,css trong themes ra ngoài public của laravel.
    + `Reset` reset lại toàn bộ cấu hình của themes

## Document
### List
- Home page: `display_label|relation|find_by_field|value|sort_by_field|sort_algo|limit|show_more_url`
####
    Phim chiếu rạp mới||is_shown_in_theater|1|created_at|desc|8|/danh-sach/phim-chieu-rap
    Phim bộ mới||type|series|updated_at|desc|8|/danh-sach/phim-bo
    Phim lẻ mới||type|single|updated_at|desc|8|/danh-sach/phim-le
    Phim hoạt hình mới|categories|slug|hoat-hinh|updated_at|desc|8|/the-loai/hoat-hinh
####

- Cột phải: `Label|relation|find_by_field|value|sort_by_field|sort_algo|limit|show_template (top_thumb|top_thumb_scroll)`
####
    Top phim lẻ||type|single|view_week|desc|10|top_thumb
    Top phim bộ||type|series|view_week|desc|10|top_thumb_scroll
    Phim sắp chiếu||status|trailer|publish_year|desc|10|top_thumb_scroll
####

### Custom View Blade
- File blade gốc trong Package: `/vendor/ophimcms/theme-tocanime/resources/views/themetocanime`
- Copy file cần custom đến: `/resources/views/vendor/themes/tocanime`

