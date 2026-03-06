package com.techzone.banhang.service;

import com.techzone.banhang.entity.ThuongHieu;

import java.util.List;

public interface ThuongHieuService {
    List<ThuongHieu> getAllThuongHieu();
    ThuongHieu getThuongHieuById(String id);
    ThuongHieu createThuongHieu(ThuongHieu thuongHieu);
    ThuongHieu updateThuongHieu(ThuongHieu thuongHieu);
    void deleteThuongHieu(String id);
}
