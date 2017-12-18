/* タイムゾーン変更　*/
ALTER DATABASE d9tvrajojdeemh SET timezone TO 'Asia/Tokyo';

/* テーブル作成　*/

CREATE TABLE tbl_toilet (
  date timestamp NOT NULL default current_timestamp,  
  place nchar(6) NOT NULL,
  status boolean NOT NULL,
  PRIMARY KEY(date, place)
);

/* 行表示 */
select * from tbl_toilet;

/* 行作成 */

INSERT INTO tbl_toilet (
place, status
) VALUES (
'1F',
false
);

/* 行更新 */

UPDATE tbl_toilet 
SET 
  date =  current_timestamp,
  status = false
WHERE
place = '1F';
