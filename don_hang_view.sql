-- View to calculate actual payment amount considering discounts
CREATE VIEW v_don_hang_with_discount AS
SELECT 
    dh.ma_don_hang,
    dh.ma_user,
    dh.ma_dia_chi,
    dh.ma_khuyen_mai,
    dh.tong_tien_hang,
    COALESCE(km.tien_khuyen_mai, 0) AS tien_khuyen_mai,
    (dh.tong_tien_hang - COALESCE(km.tien_khuyen_mai, 0)) AS thanh_toan_calculated,
    dh.thanh_toan AS thanh_toan_luu_tru,
    dh.trang_thai_don_hang,
    dh.ngay_tao
FROM 
    don_hang dh
LEFT JOIN 
    khuyen_mai km ON dh.ma_khuyen_mai = km.ma_khuyen_mai;