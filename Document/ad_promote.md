# 广告推荐 promote 新接口

    接口描述 : http://s1.picsjoin.com/Ad_Promote/public/getAdConf?country_code=default

    接口参数 :

        country_code : CN、US、IN... (不传按照 IP 获得, 否则按照传入参数进行数据获取)

        下面的参数使用 post 方式，在请求主体中传一个 json 格式

        ```javascript
        {
            "package_name": "test.test",
            "statue": "1",
            "ad_units": [
                {
                    "ad_unit_id":"123",
                    "max_request_count":4

                },
                {
                    "ad_unit_id":"456",
                    "max_request_count":2

                }
            ]
        }
        ```

    接口返回 JSON 格式 :

        ```javascript
        {
            "status": 1,
            "msg": "",
            "server_time": 1528352557,
            "data": [
                {
                    "ad_unit_id": 123,
                    "ad_unit_alias": "test",
                    "package_name": "test.test",
                    "adinfo": [
                        {
                            "ad_id": 112,
                            "iconUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/test1",
                            "imageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/test2",
                            "adImageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/test3",
                            "bannerImageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/test4",
                            "adTarget": "test",
                            "adStarRate": 0,
                            "adTitle": "test",
                            "adBody": "tttt",
                            "callAction": "button",
                            "sourceType": "down"
                        },
                        {
                            "ad_id": 223,
                            "iconUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/ttt",
                            "imageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/ttt",
                            "adImageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/tt",
                            "bannerImageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/tt",
                            "adTarget": "tt",
                            "adStarRate": 0,
                            "adTitle": "tt",
                            "adBody": "t",
                            "callAction": "t=bu",
                            "sourceType": "down"
                        }
                    ]
                },
                {
                    "ad_unit_id": 456,
                    "ad_unit_alias": "test2",
                    "package_name": "test.test",
                    "adinfo": [
                        {
                            "ad_id": 112,
                            "iconUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/test1",
                            "imageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/test2",
                            "adImageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/test3",
                            "bannerImageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/test4",
                            "adTarget": "test",
                            "adStarRate": 0,
                            "adTitle": "test",
                            "adBody": "tttt",
                            "callAction": "button",
                            "sourceType": "down"
                        },
                        {
                            "ad_id": 223,
                            "iconUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/ttt",
                            "imageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/ttt",
                            "adImageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/tt",
                            "bannerImageUrl": "http://shuguangwuxian.oss-us-west-1.aliyuncs.com/tt",
                            "adTarget": "tt",
                            "adStarRate": 0,
                            "adTitle": "tt",
                            "adBody": "t",
                            "callAction": "t=bu",
                            "sourceType": "down"
                        }
                    ]
                }
            ],
            "country_code": null
        }
        ```
