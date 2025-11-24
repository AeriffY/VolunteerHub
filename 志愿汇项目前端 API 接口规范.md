### **志愿汇项目前端 API 接口规范** 

**目的:** 此文档列出了前端所有 Blade 模板中通过 route(...) 函数调用的后端路由（Endpoints），用于指导后端团队实现相应的 Controller 方法和业务逻辑。

Controllers（有更改，后端可自行设计匹配url和route）:
UserController(register, login, logout, viewProfile), 删除了editProfile
ActivityController(createActivity, storeActivity, editActivity, updateActivity, cancelActivity, listActivities, viewActivity), 新增storeActivity, updateActivity
RegistrationController(registerForActivity, cancelRegistration),
CheckinController(gotoCheckin, storeCheckin, generateCheckinCode), 删除了checkinUser，新增gotoCheckin, storeCheckin
HoursController(viewTotalHours, generateProof) viewTotalHours感觉可以删除

### **一、UserController**

| 路由方法 | 路由 URI | 路由名称 (Name) | 对应controller函数 | 作用描述 |
| :---- | :---- | :---- | :---- | :---- |
| **GET** | /login | login | (N/A, 由路由直接返回视图) | **显示**登录表单页面 (view('auth.login'))。 |
| **POST** | /login | login | UserController@login | **处理** login.blade.php 提交的登录数据。**成功后重定向到活动列表页 (`activities.index/admin.activities.index`)** |
| **GET** | /register | register | (N/A, 由路由直接返回视图) | **显示**注册表单页面 (view('auth.register'))。 |
| **POST** | /register | register | UserController@register | **处理** register.blade.php 提交的注册数据。**成功后重定向到活动列表页 (`activities.index`)** |
| **POST** | /logout | logout | UserController@logout | **处理**用户的登出请求。销毁 Session，然后**重定向到登录页 (/login)**。 |
| **GET** | /profile | profile.show | UserController@viewProfile | **跳转个人中心** |

### 

### **二、ActivityController**

| 路由方法 | 路由 URI | 路由名称 (Name) | 对应controller函数 | 作用描述 |
| :---- | :---- | :---- | :---- | :---- |
| **GET** | /activities | activities.index | ActivityController@listActivities | **活动列表与搜索。** 显示所有已发布的活动。接收可选的 search 查询参数进行标题/地点模糊查询。 |
| **GET** | /admin/activities | admin.activities.index | ActivityController@listActivities | **获取管理员活动列表。** (管理后台主页)。 |
| **GET** | /activities/{activity} | activities.show | ActivityController@viewActivities  | **活动详情页。** 显示单个活动的详细信息，并包含报名、签到按钮/状态（基于用户是否已报名） |
| **GET** | /admin/activities/create | admin.activities.create | AdminActivityController@createActivity | **显示**创建新活动的表单页面。 |
| **POST** | /admin/activities | admin.activities.store | AdminActivityController@storeActivity | **处理**创建新活动的请求。成功后**重定向到活动列表页(admin.activities)**。 |
| **GET** | /admin/activities/{activity}/edit | admin.activities.edit | AdminActivityController@editActivity | **显示**编辑现有活动的表单页面。 |
| **PUT/PATCH** | /admin/activities/{activity} | admin.activities.update | AdminActivityController@updateActivity | **处理**编辑活动的请求。成功后**重定向到活动列表页**。(admin.activities) |
| **DELETE** | /admin/activities/{activity} | admin.activities.destroy | AdminActivityController@cancelActivity | **处理**删除活动的请求。成功后**重定向到管理员活动列表页**。(admin.activities) |

### **三、 RegistrationController**

| 路由方法 | 路由 URI | 路由名称 (Name) | 对应controller函数 | 作用描述 |
| :---- | :---- | :---- | :---- | :---- |
| **POST** | /registrations/{activity\_id} | registrations.store | RegistrationController@registerForActivity | **报名活动。** 志愿者点击“立即报名”后发起，成功后**重定向回活动详情页**，(activities.show)显示报名成功状态。 |
| **DELETE** | /registrations/{registration\_id} | registrations.destroy | RegistrationController@ccancelRegistration | **取消报名。** 志愿者点击“取消报名”后发起，成功后**重定向回活动详情页(activities.show)**，显示未报名状态。 |

### 

### **四、CheckinController**

| 路由方法 | 路由 URI | 路由名称 (Name) | 对应controller函数 | 作用描述 |
| :---- | :---- | :---- | :---- | :---- |
| **GET** | /checkin/{activity}/create | checkin.create | CheckinController@gotoCheckin | **前往签到页进行签到。** 接收活动 ID，用于展示签到流程或接口（后端需实现）。 |
| **POST** | /checkin/{activity} | checkin.create | CheckinController@storeCheckin | **储存签到结果** |
| **POST** | admin/activities/{activity}/generatecode | admin.activities.generatecode   | CheckinController@generateCheckinCode | **生成签到码。** 接收活动 ID，用于触发签到码的生成和展示。 |

### **五、HoursController**

| 路由方法 | 路由 URI | 路由名称 (Name) | 对应controller函数 | 作用描述 |
| :---- | :---- | :---- | :---- | :---- |
| **GET** | /profile/export-pdf | profile.exportPdf | HoursController@generateProof | **导出时长 PDF 文件。** (需要根据用户志愿时长生成文件并返回)。 |

