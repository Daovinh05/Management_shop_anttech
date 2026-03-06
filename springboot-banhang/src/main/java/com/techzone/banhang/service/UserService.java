package com.techzone.banhang.service;

import com.techzone.banhang.entity.User;

import java.util.List;
import java.util.Optional;

public interface UserService {

    List<User> getAllUsers();

    Optional<User> getUserById(String id);

    Optional<User> getUserByUsername(String username);

    Optional<User> getUserByEmail(String email);

    User createUser(User user);

    User updateUser(User user);

    void deleteUser(String id);

    boolean existsByUsername(String username);

    boolean existsByEmail(String email);

    boolean existsByPhone(String phone);

    Optional<User> validateUser(String username, String password);
}
