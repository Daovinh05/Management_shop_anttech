package com.techzone.banhang.service;

import com.techzone.banhang.entity.DanhMuc;

import java.util.List;

public interface DanhMucService {
    List<DanhMuc> getAllDanhMuc();
    DanhMuc getDanhMucById(String id);
    DanhMuc createDanhMuc(DanhMuc danhMuc);
    DanhMuc updateDanhMuc(DanhMuc danhMuc);
    void deleteDanhMuc(String id);
}
