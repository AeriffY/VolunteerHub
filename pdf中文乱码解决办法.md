##### 运行composer require barryvdh/laravel-dompdf

### pdf中文乱码解决办法

##### 1.下载类库语言安装脚本https://github.com/dompdf/utils.git

##### 2.将load_font.php拷贝到项目根目录

##### 3.下载宋体SimSun.ttf也放到根目录（注意名字可变）

##### 4.命令行执行php load_font.php SimSun.ttf SimSun

##### 5.php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"

##### 6.将vendor\dompdf\dompdf\lib\fonts 文件加下的全部字体到 storage/fonts 文件夹下（没有则创建）