create database hospital_1;   -- added create database
\c hospital_1;
create table patient(pid varchar(5) primary key,phno varchar(10) not null, pname varchar(20) not null, dob date not null, addres varchar(30) not null,sex varchar(1) check (sex in ('m','f','o')), ptype varchar(3) not null check (ptype in ('ipd','opd')));
create table doctor(doc_id varchar(5) primary key,qual varchar(20) not null, consultn money not null, empid varchar(5) not null);
create table nurse(nurseid varchar(5) primary key,patientcount int not null, empid varchar(5) not null);
create table department(deptid varchar(5) primary key,deptname varchar(10) not null,deptloc text not null);
create table employee(empid varchar(5) primary key, ename varchar(20) not null, gender varchar(1) check(gender in ('m','f', 'o')), joindate date not null, dob date not null, phno varchar(10), salary money check(salary::numeric>50000 and emp_type='doc' and leave_date=NULL), leave_date date, emp_type varchar(3) not null check(emp_type in ('doc', 'nur')), deptid varchar(5));
alter table employee add foreign key(deptid) references department(deptid);
create table Out_Patient(OPD_ID varchar(5) primary key,D_Arrival date not null,Disease varchar(50) not null,Pid varchar(5) not null);
create table in_patient(ipd_id varchar(5) primary key,d_disease varchar(20) not null,d_arrival date not null,d_discharge date,pid varchar(5) not null,room_id varchar(5) not null);
create table relative(patient_ipd_id varchar(5),relative_name varchar(20) not null,relation varchar(20) not null,phone_number varchar(10) not null, primary key(patient_ipd_id,relative_name), foreign key(patient_ipd_id) references in_patient(ipd_id));
create table room(room_id varchar(5) primary key,room_cost money not null,room_type text);
create table nurse_assigned(nurse_id varchar(5),patient_ipd_id varchar(5) not null, primary key(nurse_id, patient_ipd_id));
create table doctor_assigned(doc_id varchar(5),patient_id varchar(5), primary key(doc_id,patient_id));
create table test(test_id varchar(5) primary key,test_name text not null,test_cost money not null);
--alter table test add primary key(pid);
--alter table test add primary key(test_id,pid);
create table medicine(med_id varchar(10) primary key,med_name text not null,med_cost money not null);
create table bill(bill_id varchar(5) primary key,other_charges money not null);
create table takes(med_id varchar(5) not null,patient_id varchar(5) not null,m_date date,qty int not null, primary key(med_id,patient_id));
create table has(test_id varchar(5), pid varchar(5), tdate date not null, primary key(test_id, pid), foreign key(pid) references patient(pid), foreign key(test_id) references test(test_id));   --added this new table
alter table takes add foreign key(med_id) references medicine(med_id);
alter table takes add foreign key(patient_id) references patient(pid);
alter table doctor add foreign key(empid) references employee(empid);
alter table nurse add foreign key(empid) references employee(empid);
alter table doctor_assigned add foreign key(doc_id) references doctor(doc_id);
alter table doctor_assigned add foreign key(patient_id) references patient(pid);
alter table nurse_assigned add foreign key(nurse_id) references nurse(nurseid);
alter table nurse_assigned add foreign key(patient_ipd_id) references in_patient(ipd_id);
alter table in_patient add foreign key(pid) references patient(pid);
alter table in_patient add foreign key(room_id) references room(room_id);
alter table out_patient add foreign key(pid) references patient(pid);
alter table bill add pid varchar(5) not null;
alter table bill add foreign key(pid) references patient(pid);
alter table bill add bill_date date not null;
-- alter table test add test_date date not null;
alter table department alter column deptname type varchar(30);
insert into department values('DPT01','Anaesthetics','BSK STG 2, Bangalore');
insert into department values('DPT02','ENT','BSK STG 2, Bangalore');
insert into department values('DPT03','Gastroentrology','BSK STG 2, Bangalore');
insert into department values('DPT04','General Surgery','BSK STG 2, Bangalore');
insert into department values('DPT05','Gynaecology','BSK STG 2, Bangalore');
insert into department values('DPT06','Medicine','BSK STG 2, Bangalore');
insert into department values('DPT07','Ortho','BSK STG 2, Bangalore');
insert into department values('DPT08','Cardiac','BSK STG 2, Bangalore');
insert into employee values('EM001','John','m','05-14-2010','09-10-1981','9987367263',55000,null,'doc','DPT06');
insert into employee values('EM002','Sunil','m','09-1-2011','11-10-1981','9987360063',200000,null,'doc','DPT08');
insert into employee values('EM003','Sheetal','f','10-10-2012','11-10-1989','9850860063',100000,null,'doc','DPT05');
alter table employee drop constraint employee_check;
insert into employee values('EM004','Mary','f','10-10-2009','11-10-1993','7851160063',10000,null,'nur',null);
insert into employee values('EM005','Reema','f','10-10-2009','1-10-1993','7851457063',10000,null,'nur',null);
insert into employee values('EM006','Seema','f','10-10-2009','1-1-1993','7751907063',10000,null,'nur',null);
insert into doctor values('DOC01','MBBS',500,'EM001');
insert into doctor values('DOC02','MBBS,MD,DM',2000,'EM002');
insert into doctor values('DOC03','MBBS,MD',1000,'EM003');
insert into nurse values('NUR01',1,'EM004');
insert into nurse values('NUR02',1,'EM005');
insert into nurse values('NUR03',1,'EM006');
insert into patient values('PA001','9611142574','Jacob','05-04-1991','Vijaynagar, Bangalore','m','opd');
insert into patient values('PA002','961114876','Paul','10-24-1965','Kormangala, Bangalore','m','ipd');
insert into patient values('PA003','9611798772','Chaya','05-04-1990','Attiguppe, Bangalore','f','ipd');
insert into out_patient values('OPD01','1-1-2011','Cold','PA001');
insert into room values('R001',4000,'single');
insert into room values('R002',2000,'double');
insert into room values('R003',1000,'dorm');
insert into in_patient values('IPD01','Heart-Attack','1-1-2013','2-1-2013','PA002','R001');
insert into in_patient values('IPD02','Gestational-Diabetes','1-7-2013','1-14-2013','PA003','R002');
insert into nurse_assigned values('NUR01','IPD01');
insert into nurse_assigned values('NUR02','IPD01');
insert into nurse_assigned values('NUR03','IPD02');
insert into doctor_assigned values('DOC01','PA001');
insert into doctor_assigned values('DOC02','PA002');
insert into doctor_assigned values('DOC03','PA003');
insert into medicine values('MED01','Sinarest',10);
insert into medicine values('MED02','Citzin',10);
insert into medicine values('MED03','Aspirin',1000);
insert into medicine values('MED04','Wararin',2000);
insert into medicine values('MED05','Insulin-A',600);
insert into medicine values('MED06','Glynase',700);
insert into test values('TES01','Troponin Test',1500);
insert into test values('TES02','CK-MB Test',2000);
insert into test values('TES03','Serum Myoglobin Test',1700);
insert into test values('TES04','Random Blood Sugar Test',500);
insert into test values('TES05','Fasting Blood Sugar Test',500);
insert into test values('TES06','Oral Glucose Tolerence Test',1000);
insert into relative values('IPD01','Sonal','Sister','9988663526');
insert into relative values('IPD02','Brajesh','Brother','7876289101');
insert into has values('TES01','PA002','1-1-2013');
insert into has values('TES02','PA002','1-1-2013');
insert into has values('TES03','PA002','1-1-2013');
insert into has values('TES06','PA003','1-7-2013');
insert into bill values('BIL01',100000,'PA002','2-1-2013');
insert into bill values('BIL02',5000,'PA003','1-14-2013');














































