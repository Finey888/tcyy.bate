/*
SQLyog Ultimate v12.08 (64 bit)
MySQL - 5.7.24 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `tcyy_base_dict` (
	`id` int (11),
	`dict_code` varchar (45),
	`dict_value` tinyint (4),
	`dict_name` varchar (150),
	`dict_sort` int (11),
	`remark` varchar (150)
); 
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('positionType','1','全科医师','1','职位类型');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('positionType','2','口腔护士','2','职位类型');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('positionType','3','口腔种植','3','职位类型');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('positionType','4','口腔正畸','4','职位类型');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('positionType','5','口腔修复','5','职位类型');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('positionType','6','口腔外科','6','职位类型');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('positionType','7','口腔内科','7','职位类型');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('positionType','8','口腔美容','8','职位类型');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('positionType','9','口腔保健','9','职位类型');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('positionType','10','儿童牙科','10','职位类型');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('education','99','应届毕业生','1','学历');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('education','1','大专','2','学历');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('education','2','本科','3','学历');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('education','3','硕士','4','学历');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('education','4','博士','5','学历');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('salaryRange','99','面议','0','薪酬范围');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('salaryRange','1','3000元以下','1','薪酬范围');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('salaryRange','2','3000-5000元','2','薪酬范围');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('salaryRange','3','5000-10000元','3','薪酬范围');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('salaryRange','4','10000-20000元','4','薪酬范围');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('salaryRange','5','20000元以上','5','薪酬范围');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('workExperience','1','1年以下','1','工作经验');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('workExperience','2','1-3年','2','工作经验');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('workExperience','3','3-5年','3','工作经验');
insert into `tcyy_base_dict` ( `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('workExperience','4','5年以上','4','工作经验');
