### **志愿汇项目前端 API 接口规范 (完整版)**

**目的:** 此文档列出了前端所有 Blade 模板中通过 route(...) 函数调用的后端路由（Endpoints），用于指导后端团队实现相应的 Controller 方法和业务逻辑。

### **一、用户活动与报名接口 (ActivityController & RegistrationController)**

这部分接口主要用于普通用户浏览活动、搜索、查看详情以及报名/取消报名/签到。

| 编号 | 路由方法 | 路由 URI | 路由名称 (Name) | 视图文件 | 作用描述 |
| :---- | :---- | :---- | :---- | :---- | :---- |
| **A1** | GET | /activities | activities.index | index.blade.php | **获取活动列表** (活动广场)。支持通过 GET 参数 ?search=... 进行搜索和过滤。 |
| **A2** | GET | /activities/{activity} | activities.show | index.blade.php, show.blade.php | **查看活动详情。** |
| **A3** | POST | /registrations/{activity} | registrations.store | show.blade.php | **报名活动。** 接收活动 ID，为当前用户创建报名记录。 |
| **A4** | DELETE | /registrations/{registration} | registrations.destroy | show.blade.php | **取消报名。** 接收报名记录 ID，删除报名记录。 |
| **A5** | GET | /checkin/{activity} | checkin.create | show.blade.php | **前往签到页。** 接收活动 ID，用于展示签到流程或接口（后端需实现）。 |

### **二、个人中心接口 (ProfileController)**

这部分接口用于用户查看个人信息和活动记录。

| 编号 | 路由方法 | 路由 URI | 路由名称 (Name) | 视图文件 | 作用描述 |
| :---- | :---- | :---- | :---- | :---- | :---- |
| **P1** | GET | /profile/export-pdf | profile.exportPdf | show.blade.php (Profile) | **导出时长 PDF 文件。** (需要根据用户志愿时长生成文件并返回)。 |
| **P2** | GET | /profile | profile.show | show.blade.php (Profile) | **个人中心页面。** 需要获取用户累计时长 ($hours-\>total\_hours) 和报名记录 ($registrations)。 |

### **三、管理后台接口 (AdminActivityController)**

这部分接口需要 auth 认证和 role:admin 中间件保护，用于管理员进行活动管理操作。

| 编号 | 路由方法 | 路由 URI | 路由名称 (Name) | 视图文件 | 作用描述 |
| :---- | :---- | :---- | :---- | :---- | :---- |
| **M1** | GET | /admin/activities | admin.activities.index | app.blade.php | **获取管理员活动列表。** (管理后台主页)。 |
| **M2** | POST | /admin/activities | admin.activities.store | create.blade.php | **存储新活动。** 接收表单数据（title, description, start\_time 等）。 |
| **M3** | PUT | /admin/activities/{activity} | admin.activities.update | edit.blade.php | **更新活动。** 接收活动 ID，以及完整的表单数据。 |
| **M4** | DELETE | /admin/activities/{activity} | admin.activities.destroy | index.blade.php (Admin List) | **删除活动。** 接收活动 ID。 |
| **M5** | POST | /admin/activities/{activity}/generate-code | admin.activities.generateCode | index.blade.php (Admin List) | **生成签到码。** 接收活动 ID，用于触发签到码的生成和展示。 |
| **M6** | GET | /admin | admin.dashboard | N/A | **管理员仪表盘入口。** 约定重定向到 admin.activities.index。 |
| **M7** | GET | /admin/activities/create | admin.activities.create | N/A (仅路由名) | **显示创建活动表单。** |
| **M8** | GET | /admin/activities/{activity}/edit | admin.activities.edit | N/A (仅路由名) | **显示编辑活动表单。** |

### **四、认证与密码重置接口 (Auth & Password Reset)**

这部分接口通常由 Laravel 内建认证系统提供，但为了清晰，也需列出。

| 编号 | 路由方法 | 路由 URI | 路由名称 (Name) | 视图文件 | 作用描述 |
| :---- | :---- | :---- | :---- | :---- | :---- |
| **R1** | POST | /logout | logout | app.blade.php | **用户登出。** |
| **R2** | POST | /login | login | login.blade.php | **用户登录。** |
| **R3** | POST | /register | register | register.blade.php | **用户注册。** |
| **R4** | POST | /email/resend | verification.resend | verify.blade.php | **重新发送邮箱验证链接。** |
| **R5** | POST | /password/email | password.email | email.blade.php | **发送密码重置邮件。** |
| **R6** | GET | /password/reset | password.request | login.blade.php, confirm.blade.php | **显示忘记密码链接页面。** |
| **R7** | POST | /password/reset | password.update | reset.blade.php | **更新用户密码** (密码重置流程)。 |
| **R8** | POST | /user/confirm-password | password.confirm | confirm.blade.php | **确认密码** (用于敏感操作前的二次验证)。 |

