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

Models:  
User(basic information/roles:admin, volunteer),  
Activity(title, description, time, location, capacity, status),  
Registration(user, activity, registration_time, status),  
Checkin(activity, user, timestamp),  
Hours(user, total_hours),  

Controllers:  
UserController(register, login, viewProfile, editProfile),  
ActivityController(createActivity, editActivity, cancelActivity, listActivities, viewActivity),  
RegistrationController(registerForActivity, cancelRegistration),  
CheckinController(generateCheckinCode, checkinUser),  
HoursController(viewTotalHours, generateProof)  

Middleware