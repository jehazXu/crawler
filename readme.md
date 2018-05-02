

## About Crawler


- git 克隆到本地
- 项目目录下执行composer update (服务器需安装composer)
- 项目目录下执行 php artisan migrate:fresh 初始化数据库
- 服务器root身份执行 crontab -e 编辑定时任务，在最后一行添加 * * * * * /usr/local/php72/bin/php /data/wwwroot/default/crawler/artisan schedule:run >> /dev/null 2>&1 (必须使用绝对路径)


-京东 https://count.taobao.com/counter3?_ksTS=1524548798552_254&callback=jsonp255&keys=SM_368_dsr-2911805119,ICCP_1_537144603490
-天猫 https://mdskip.taobao.com/core/initItemDetail.htm?isUseInventoryCenter=true&cartEnable=true&service3C=false&isApparel=false&isSecKill=false&tmallBuySupport=true&isAreaSell=true&tryBeforeBuy=false&volume=10&offlineShop=false&itemId=537144603490&showShopProm=false&cachedTimestamp=1524533041079&isPurchaseMallPage=false&isRegionLevel=true&household=true&sellerPreview=false&manufactureCity=%B3%C9%B6%BC%CA%D0&queryMemberRight=true&addressLevel=4&isForbidBuyItem=false&callback=setMdskip&timestamp=1524554688478&isg=null&isg2=BIKCcTzqbIOzB3BfaCUY0z6k0ogk-4bZhSg4asyZyvWgHyOZtOOZfjM-yxtjT_4F&cat_id=2&ref=https%3A%2F%2Flist.tmall.com%2Fsearch_product.htm%3Fq%3D%25C9%25B3%25B7%25A2%26type%3Dp%26vmarket%3D%26spm%3D875.7931836%252FB.a2227oh.d100%26from%3Dmallfp..pc_1_searchbutton