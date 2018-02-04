# nfangbian-entry
nfangbian.com,nfangbian,entry 

###考虑点
```
1. 环境多元化: dev、st、prev、production、(1-N)、
2. 语言问题: 完全分离&隔离,这里不考虑php语言以外的项目(如java、nodejs、c/c++、python、go)
3. 统一入口: 方便统一管理、入口方式web、cli等
4. 项目问题: a) 业务多变 b)php框架多样 c)系统名及系统子模块化
5. 运维管理: 方便统一管理和维护, code发布,监控
6. 配置问题: a) 配置的优先级及覆盖 b)配置项可继承
7. php运行容器兼容: nginx、apache、iis
8. php运行操作系统: windows、mac、linux(centos、ubuntu、Fedora、RedHat、Debian) etc
9. php代码发布: 代码切换间的时间空隙如何确保项目稳定运行(code、db、nosql cache)
10. domain: 开发者域名、st测试环境域名、集成环境域名、预发布环境域名、生产环境域名
11.
```

###code dir目录
```
/data1/www/[php*]
    |- entrycode #all php项目入口
        |- lib #入口代码依赖的类库及方法等
            |- xxx目录
            |- common.php
            |- ...
        |- console
            |- artisan #console代码入口   php artisan 项目名 --cloud=开发者 --env=环境代号
        |- public   #nginx web项目入口,nginx config中传入对应的标识给index.php, 如项目名、子模块、环境代号etc.
            |- index.php
    |- config  #all php项目配置,配置格式任意,由运维维护,如.env、.env.example、global.yml、global.properties、common.php、db.php、redis.php etc.
        |- .env.example  #all项目配置
        |- production  #环境代号 dev、st、prev
            |- 项目名 #关于该项目等配置,配置格式任意
                |- .env.example
    |- projectcode  #all php项目代码发布这里
        |- 项目名  #项目code
            |- .env.example
            |- public
                |- index.php
            |- artisan
        |- 项目名N
            |- 项目code
        |- 开发者名
            |- 项目名
                |- 项目code
```

