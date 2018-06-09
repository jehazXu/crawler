

## About Crawler


- git 克隆到本地
- 项目目录下执行composer update (服务器需安装composer)
- 项目目录下执行 php artisan migrate:fresh 初始化数据库
- 服务器root身份执行 crontab -e 编辑定时任务，在最后一行添加 * * * * * /usr/local/php72/bin/php /data/wwwroot/default/crawler/artisan schedule:run >> /dev/null 2>&1 (必须使用绝对路径)
