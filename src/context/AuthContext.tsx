import React, { createContext, useContext, useState, useEffect } from 'react';
import { RoleKey, UserRoleConfig } from '../types';

export const USER_ROLES: Record<RoleKey, UserRoleConfig> = {
  doc: {
    key: 'doc',
    name: 'Director of Compliance',
    roleBadge: 'ADMIN (DoC)',
    avatar: 'DC',
    email: 'director@cccrn.org',
    allowedModules: ['dashboard', 'leave-attendance', 'complaints', 'cap', 'pdp', 'training', 'states', 'risk', 'policy', 'lessons', 'reports', 'ai', 'ai-review', 'investigation', 'travel'],
    defaultModule: 'dashboard'
  },
  compliance_officer: {
    key: 'compliance_officer',
    name: 'Compliance Officer or Specialist',
    roleBadge: 'COMPLIANCE SPECIALIST',
    avatar: 'CO',
    email: 'compliance@cccrn.org',
    allowedModules: ['complaints', 'cap', 'training', 'states', 'risk', 'lessons', 'investigation', 'travel'],
    defaultModule: 'complaints'
  },
  hr: {
    key: 'hr',
    name: 'HR (Human Resources)',
    roleBadge: 'HR ACCESS',
    avatar: 'HR',
    email: 'hr@cccrn.org',
    allowedModules: [
      'dashboard',
      'leave-attendance',
      'complaints',
      'cap',
      'pdp',
      'training',
      'states',
      'risk',
      'policy',
      'lessons',
      'ai-review',
      'investigation',
      'travel'
    ],
    defaultModule: 'dashboard'
  },
  staff: {
    key: 'staff',
    name: 'All Staff',
    roleBadge: 'STAFF ACCESS',
    avatar: 'ST',
    email: 'staff@cccrn.org',
    allowedModules: ['complaints', 'cap', 'pdp', 'training', 'states', 'policy', 'lessons', 'ai', 'travel'],
    defaultModule: 'complaints'
  }
};

interface AuthContextType {
  currentUser: UserRoleConfig | null;
  activeModule: string;
  setActiveModule: (module: string) => void;
  login: (roleKey: RoleKey) => void;
  logout: () => void;
  isDocAdmin: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [currentUser, setCurrentUser] = useState<UserRoleConfig | null>(() => {
    const saved = sessionStorage.getItem('CCCRN_REACT_ROLE');
    return saved && USER_ROLES[saved as RoleKey] ? USER_ROLES[saved as RoleKey] : null;
  });

  const [activeModule, setActiveModuleState] = useState<string>(() => {
    const saved = sessionStorage.getItem('CCCRN_REACT_ROLE');
    if (saved && USER_ROLES[saved as RoleKey]) {
      return USER_ROLES[saved as RoleKey].defaultModule;
    }
    return 'complaints';
  });

  useEffect(() => {
    if (currentUser) {
      if (!currentUser.allowedModules.includes(activeModule)) {
        setActiveModuleState(currentUser.defaultModule);
      }
    }
  }, [currentUser, activeModule]);

  const setActiveModule = (module: string) => {
    if (!currentUser) return;
    if (currentUser.allowedModules.includes(module)) {
      setActiveModuleState(module);
    } else {
      console.warn(`Access Denied: Role ${currentUser.key} is not authorized to access ${module}`);
    }
  };

  const login = (roleKey: RoleKey) => {
    const user = USER_ROLES[roleKey];
    if (user) {
      sessionStorage.setItem('CCCRN_REACT_ROLE', roleKey);
      setCurrentUser(user);
      setActiveModuleState(user.defaultModule);
    }
  };

  const logout = () => {
    sessionStorage.removeItem('CCCRN_REACT_ROLE');
    setCurrentUser(null);
  };

  const isDocAdmin = currentUser?.key === 'doc';

  return (
    <AuthContext.Provider value={{ currentUser, activeModule, setActiveModule, login, logout, isDocAdmin }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within an AuthProvider');
  return ctx;
};
