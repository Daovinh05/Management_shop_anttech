-- Alternative: Update existing records to reflect correct payment calculation
UPDATE don_hang dh
LEFT JOIN khuyen_mai km ON dh.ma_khuyen_mai = km.ma_khuyen_mai
SET dh.thanh_toan = dh.tong_tien_hang - COALESCE(km.tien_khuyen_mai, 0);

-- Or if you want to create a trigger to automatically calculate thanh_toan when inserting/updating
DELIMITER $$

CREATE TRIGGER tr_don_hang_calculate_payment_insert
BEFORE INSERT ON don_hang
FOR EACH ROW
BEGIN
    DECLARE discount_amount DECIMAL(15,2);
    
    IF NEW.ma_khuyen_mai IS NOT NULL THEN
        SELECT tien_khuyen_mai INTO discount_amount
        FROM khuyen_mai
        WHERE ma_khuyen_mai = NEW.ma_khuyen_mai;
        
        SET NEW.thanh_toan = NEW.tong_tien_hang - COALESCE(discount_amount, 0);
    ELSE
        SET NEW.thanh_toan = NEW.tong_tien_hang;
    END IF;
END$$

CREATE TRIGGER tr_don_hang_calculate_payment_update
BEFORE UPDATE ON don_hang
FOR EACH ROW
BEGIN
    DECLARE discount_amount DECIMAL(15,2);
    
    IF NEW.ma_khuyen_mai IS NOT NULL THEN
        SELECT tien_khuyen_mai INTO discount_amount
        FROM khuyen_mai
        WHERE ma_khuyen_mai = NEW.ma_khuyen_mai;
        
        SET NEW.thanh_toan = NEW.tong_tien_hang - COALESCE(discount_amount, 0);
    ELSE
        SET NEW.thanh_toan = NEW.tong_tien_hang;
    END IF;
END$$

DELIMITER ;