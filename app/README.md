基础 URL 为 http://127.0.0.1:8000/api, 所有请求与响应使用 JSON 格式.

我们采用 Laravel  Sanctum 来进行身份验证, 关于 Sanctum 请参阅 https://docs.golaravel.com/docs/10.x/sanctum, 具体来说, 可能需要在终端运行 `composer require laravel/sanctum `并随后运行 `php artisan migrate`
前端应将用户的 Token 放在 HTTP 请求头的 Authorization 字段中, 并且使用 Bearer 方案.

没有前端网页时请使用 Postman 或其他 API 测试工具进行测试.

### 1. 用户登录

已创建的用户在本浏览器首次登录时, 会获得一个 Samctum 访问令牌, 也就是 token. 对于 token, 正如前文所说的, 客户端应该将 Token 保存在本地, 并在每次需要认证的 API 请求中将 Token 以特定格式附加到 HTTP 请求头中.

URL: /login

Method: POST

权限: 无

请求参数如下:

| 参数名     | 类型   | 必填 | 示例             |
| ---------- | ------ | ---- | ---------------- |
| `email`    | string | 是   | `admin@test.com` |
| `password` | string | 是   | `123456`         |



请求成功返回201:

```json
{
    "message": "login success",
    "accessToken": "...", 
    "tokenType": "Bearer",
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@test.com",
        "role": "admin",
        "email_verified_at": "2025-11-23T06:00:00.000000Z",
        "created_at": "2025-11-23T06:00:00.000000Z",
        "updated_at": "2025-11-23T06:00:00.000000Z"
    }
}
```



账号或密码不正确返回401:

```JSON
{
    "message": "IncorrectAccountOrPassword"
}
```



email 或 password 参数格式错误将返回:

```JSON
{
    "message": "The email field must be a valid email address. (and 1 more error)",
    "errors": {
        "email": [
            "The email field must be a valid email address."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```



详见 /app/Http/Controllers/UserController.php



### 2. 用户退出登录

URL: /logout

Method: POST

权限: 需要登录

用户退出登录时, 将销毁其 Access Token, 该 HTTP 请求头必须携带 Token.

成功返回200:

```Json
{
    "message": "logoutSuccess"
}
```

失败返回401(Token无效或过期了):

```JSON
{
    "message": "Unauthenticated."
}
```



### 3. 活动报名

URL: /activities/{activity}/register	此处 {activity} 表示特定活动的 id. 获取活动 id 的 index 接口我将在晚些时候给出.

Method: POST

权限: 需要登录

所有需要登录权限的操作都请在 HTTP 请求头中附上 Token, 后续不再继续说明.

报名成功返回201:

```JSON
{
    "message": "Registration success"
}
```

活动未开始或结束返回400:

```JSON
{
    "message": "notActive"
}
```

名额已满返回400:

```JSON
{
    "message": "fullActivity"
}
```

重复报名返回409:

```JSON
{
    "message": "alreadyRegistered"
}
```



未登录或Token失效返回401:

```JSON
{
    "message": "Unauthenticated."
}
```



注意这里有两种情况返回了400, 需要时请比对具体的message值.





