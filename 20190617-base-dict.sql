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
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('1','positionType','1','口腔护士','1','职位类型');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('2','positionType','2','全科医师','2','职位类型');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('3','positionType','3','洽谈师','3','职位类型');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('4','positionType','4','儿童牙科','4','职位类型');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('5','positionType','5','口腔正畸','5','职位类型');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('6','positionType','6','口腔种植','6','职位类型');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('7','positionType','7','口腔内科','7','职位类型');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('8','education','0','应届毕业生','1','学历');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('9','education','1','大专','2','学历');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('10','education','2','本科','3','学历');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('11','education','3','硕士','4','学历');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('12','education','4','博士','5','学历');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('13','salaryRange','1','3000以下','1','薪酬范围');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('14','salaryRange','2','3000-5000','2','薪酬范围');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('15','salaryRange','3','5000-10000','3','薪酬范围');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('16','salaryRange','4','10000-20000','4','薪酬范围');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('17','salaryRange','5','20000以上','5','薪酬范围');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('18','peopleScope','1','0-20','1','人员规模');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('19','peopleScope','2','21-99','2','人员规模');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('20','peopleScope','3','21-99','3','人员规模');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('21','peopleScope','4','500-1000','4','人员规模');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('22','peopleScope','5','1000-9999','5','人员规模');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('23','workExperience','1','1年以下','1','工作经验');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('24','workExperience','2','1-3年','2','工作经验');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('25','workExperience','3','3-5年','3','工作经验');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('26','workExperience','4','5年以上','4','工作经验');
insert into `tcyy_base_dict` (`id`, `dict_code`, `dict_value`, `dict_name`, `dict_sort`, `remark`) values('28','salaryRange','0','面议','0','薪酬范围');
