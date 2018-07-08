# Setup Local Wordpress

* Username: admin
* Password: istt./2016


## Cài đặt server

Cài đặt GIT, Docker và docker-compose trên server Linux

## Bật Wordpress lên

~~~
$ # Download source code về máy, vào thư mục `wp`
$ git clone git@gitlab.com:istt.com.vn/wordpress.git wp
$
$ # Bật wordpress qua Docker
$ cd wp
$ docker-compose up -d
$
$ # Dump lại CSDL vào db
$ gunzip < clean.sql.gz | docker exec -i wp_db_1 mysql -u root wp
$
$ # Done. Visit: `http://localhost:28000/wp-login.php`
~~~

## Development GuideLines

Trên file `wp-config.php` có một số tham số cần thay đổi:

~~~php
/* FIXME: Change after done development locally */
define('WP_HOME',"http://localhost:28000" );
define('WP_SITEURL',"http://localhost:28000");
~~~

Với mỗi dự án, có thể develop trên 1 branch riêng.

Thông thường, nên chia ra các branch theo cấu trúc sau:

* `develop`: Branch chứa các tối ưu hay dùng (vd Plugins ko thể thiếu được)
* `themes/[site-name]`: Branch phát triển theme cho 1 website [site-name]
* `features/[chức-năng]`: Branch cấu hình các chức năng hoặc phát triển module.

~~~
$ # Tạo branch develop
$ git checkout -b develop
$
$ # Tạo thêm branch
$ git checkout -b features/restful-api
$
~~~
