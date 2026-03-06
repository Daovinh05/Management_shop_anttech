package com.techzone.banhang.service;

import com.techzone.banhang.entity.BienThe;

import java.util.List;

public interface BienTheService {
    List<BienThe> getAllBienThe();
    BienThe getBienTheById(String id);
    List<BienThe> findBySanPham(String maSanPham);
    BienThe createBienThe(BienThe bienThe);
    BienThe updateBienThe(BienThe bienThe);
    void deleteBienThe(String id);
}
