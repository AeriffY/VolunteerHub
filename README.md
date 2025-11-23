# VolunteerHub
An assignment built on the Laravel framework

## 功能需求
1·【基础功能】（必须完成）

i.角色管理：区分管理员(社联/协会)、普通用户（志愿者）。

ii.活动管理（管理员）：发布、编辑、取消活动（含时间、地点、人数限制、详情）。

iii.活动广场（用户）：浏览活动列表，查看详情，报名/取消报名。

iv.签到系统：活动开始时，管理员生成签到二维码/密码，用户现场扫码签到。

v.个人中心：用户查看已参与的活动列表和累计志愿服务时长。

2·【拓展功能】（选择完成，加分项）

i.工时证明：系统支持自动生成并导出志愿服务证明PDF。

ii.勋章系统：根据服务时长或参与特定活动，授予虚拟勋章。

iii.活动回顾：活动结束后可上传图文回顾。

iv.消息通知：向报名者推送活动提醒、变更通知等.  



users:
| 字段             | 类型         | 说明                                |
| -------------- | ---------- | --------------------------------- |
| id             | integer PK | 主键                                |
| name           | varchar    | 用户姓名                              |
| email          | varchar    | 邮箱（唯一）                            |
| password       | varchar    | 密码                                |
| role           | varchar    | 角色：admin / volunteer，默认 volunteer |
| remember_token | varchar    | Laravel 记住登录用                     |
| created_at     | datetime   | 创建时间                              |
| updated_at     | datetime   | 更新时间                              |



activities:
| 字段          | 类型         | 说明                   |
| ----------- | ---------- | -------------------- |
| id          | integer PK | 主键                   |
| title       | varchar    | 活动标题                 |
| description | text       | 活动描述                 |
| start_time  | datetime   | 活动开始时间               |
| end_time    | datetime   | 活动结束时间               |
| location    | varchar    | 活动地点                 |
| capacity    | integer    | 活动容量                 |
| status      | varchar    | 活动状态，默认 draft        |
| created_by  | integer FK | 关联创建者用户 ID（users.id） |
| created_at  | datetime   | 创建时间                 |
| updated_at  | datetime   | 更新时间                 |


checkins:
| 字段          | 类型         | 说明                   |
| ----------- | ---------- | -------------------- |
| id          | integer PK | 主键                   |
| activity_id | integer FK | 活动 ID（activities.id） |
| user_id     | integer FK | 用户 ID（users.id）      |
| timestamp   | datetime   | 签到时间                 |
| created_at  | datetime   | 创建记录时间               |
| updated_at  | datetime   | 更新时间                 |


registrations:
| 字段                | 类型         | 说明                                        |
| ----------------- | ---------- | ----------------------------------------- |
| id                | integer PK | 主键                                        |
| user_id           | integer FK | 报名用户 ID（users.id）                         |
| activity_id       | integer FK | 活动 ID（activities.id）                      |
| registration_time | datetime   | 报名时间                                      |
| status            | varchar    | 报名状态：registered / cancelled，默认 registered |
| created_at        | datetime   | 创建时间                                      |
| updated_at        | datetime   | 更新时间                                      |



hours:
| 字段          | 类型         | 说明              |
| ----------- | ---------- | --------------- |
| id          | integer PK | 主键              |
| user_id     | integer FK | 用户 ID（users.id） |
| total_hours | numeric    | 总工时，默认 0        |
| created_at  | datetime   | 创建时间            |
| updated_at  | datetime   | 更新时间            |
