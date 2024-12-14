CREATE DATABASE no5;
USE no5;

CREATE TABLE admins (
    id INT(10) AUTO_INCREMENT PRIMARY KEY,           
    login_id VARCHAR(20) NOT NULL UNIQUE,           
    password VARCHAR(64) NOT NULL,                   
    activated_flag INT(1) NOT NULL DEFAULT 1,        
    reset_password_token VARCHAR(100),              
    updated DATETIME ON UPDATE CURRENT_TIMESTAMP, 
    created DATETIME DEFAULT CURRENT_TIMESTAMP      
);


CREATE TABLE users (
    id INT(10) AUTO_INCREMENT PRIMARY KEY, 
    type INT(1) DEFAULT 1,                           
    name VARCHAR(250),                              
    unique_id CHAR(15) UNIQUE,                       
    avatar VARCHAR(250),                             
    description TEXT,                                
    updated DATETIME ON UPDATE CURRENT_TIMESTAMP, 
    created DATETIME       
);

CREATE TABLE events (
    id INT(10) AUTO_INCREMENT PRIMARY KEY,         
    username VARCHAR(250),                         
    slogan VARCHAR(250),                           
    leader VARCHAR(250),                            
    avatar VARCHAR(250),                           
    description TEXT,                             
    updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    created DATETIME DEFAULT CURRENT_TIMESTAMP     
);

CREATE TABLE event_timelines (
    id INT(10) AUTO_INCREMENT PRIMARY KEY,           
    event_id INT(10),                                
    from_time TIME,                                 
    to_time TIME,                                  
    name VARCHAR(250),                               
    detail TEXT,                                     
    PoC VARCHAR(250),                                
    updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    created DATETIME DEFAULT CURRENT_TIMESTAMP       
    );

CREATE TABLE event_comments (
    id INT(10) AUTO_INCREMENT PRIMARY KEY,          
    event_id INT(10),                               
    avatar VARCHAR(250),                            
    content TEXT,                                    
    updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created DATETIME DEFAULT CURRENT_TIMESTAMP       
);

