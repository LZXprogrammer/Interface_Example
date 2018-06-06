# 广告接口

    接口描述 : http://s1.picsjoin.com/Ad_library/public/getAdConf?package_name=com.baiwang.squaremaker

    接口参数 :

        package_name : 应用包名
        country_code : CN、US、IN... (不传按照 IP 获得, 否则按照传入参数进行数据获取)

    接口返回 JSON 格式 :

        ```javascript
        {
            "status": 1,                          // 状态值 1 成功 0 失败 2、3... 保留
            "msg": "",                            // 信息描述，status 为 1 则为空
            "server_time": 1511408528,            // 服务器时间戳
            "country_code": 'CN',                 // 用户所属国家 iso code
            "data": {
                "fb_id": "1",                     // fb_id
                "bat_id": "1",                    // bat_id
                "adIsRun": 0,                     // 广告是否启动
                "upIsRun": 0,                     // 更新是否启动
                "interval_time1": 1,              // 间隔时长 1
                "interval_time2": 1,              // 间隔时长 2
                "iconUrl": "1",                   // iconUrl
                "adImageUrl": "1",                // adImageUrl
                "adTarget": "test target",        // 打开链接的地址
                "adBody": "1",                    // 广告主体
                "callAction": "1",                // 按钮显示文本
                "showTimes": 1,                   // 弹出次数
                "isNewUserShow": 1,               // 新用户是否弹出
                "show_ad": 0,                     // 是否显示AD标示
                "remark1": "",                   
                "remark2": "",
                "remark3": "",
                "remark4": "",
                "remark5": ""
            }
        }
        ```
