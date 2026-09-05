import React, { useState, useMemo } from 'react';

export interface Column<T> {
  key: string;
  header: string;
  render?: (item: T) => React.ReactNode;
  sortable?: boolean;
  align?: 'left' | 'center' | 'right';
  width?: string | number;
}

interface DataTableProps<T> {
  columns: Column<T>[];
  data: T[];
  searchPlaceholder?: string;
  searchFields?: (keyof T)[];
  categories?: string[];
  categoryField?: keyof T;
  pageSize?: number;
  emptyMessage?: string;
}

export function DataTable<T extends Record<string, any>>({
  columns,
  data,
  searchPlaceholder = 'Search records...',
  searchFields,
  categories,
  categoryField,
  pageSize = 10,
  emptyMessage = 'No matching records found'
}: DataTableProps<T>) {
  const [search, setSearch] = useState('');
  const [activeCategory, setActiveCategory] = useState<string>('All');
  const [sortKey, setSortKey] = useState<string>('');
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('asc');
  const [currentPage, setCurrentPage] = useState(1);

  const filteredData = useMemo(() => {
    return data.filter(item => {
      // Category filter
      if (activeCategory !== 'All' && categoryField) {
        if (item[categoryField] !== activeCategory) return false;
      }

      // Search filter
      if (!search.trim()) return true;
      const q = search.toLowerCase();

      if (searchFields && searchFields.length > 0) {
        return searchFields.some(field => {
          const val = item[field];
          return val !== undefined && String(val).toLowerCase().includes(q);
        });
      }

      // Fallback search across all fields
      return Object.values(item).some(val =>
        val !== undefined && String(val).toLowerCase().includes(q)
      );
    });
  }, [data, search, activeCategory, categoryField, searchFields]);

  const sortedData = useMemo(() => {
    if (!sortKey) return filteredData;

    return [...filteredData].sort((a, b) => {
      const valA = a[sortKey];
      const valB = b[sortKey];

      if (valA === undefined || valB === undefined) return 0;
      if (typeof valA === 'number' && typeof valB === 'number') {
        return sortOrder === 'asc' ? valA - valB : valB - valA;
      }
      return sortOrder === 'asc'
        ? String(valA).localeCompare(String(valB))
        : String(valB).localeCompare(String(valA));
    });
  }, [filteredData, sortKey, sortOrder]);

  // Pagination
  const totalPages = Math.ceil(sortedData.length / pageSize) || 1;
  const paginatedData = useMemo(() => {
    const start = (currentPage - 1) * pageSize;
    return sortedData.slice(start, start + pageSize);
  }, [sortedData, currentPage, pageSize]);

  const handleSort = (key: string) => {
    if (sortKey === key) {
      setSortOrder(prev => prev === 'asc' ? 'desc' : 'asc');
    } else {
      setSortKey(key);
      setSortOrder('asc');
    }
  };

  return (
    <div>
      {/* Table Toolbar: Search + Category Pills */}
      <div style={{
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        flexWrap: 'wrap',
        gap: 10,
        marginBottom: 12
      }}>
        {/* Category Filter Pills */}
        {categories && categories.length > 0 ? (
          <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap', alignItems: 'center' }}>
            {['All', ...categories].map((cat) => (
              <button
                key={cat}
                onClick={() => { setActiveCategory(cat); setCurrentPage(1); }}
                style={{
                  padding: '4px 10px',
                  fontSize: 11,
                  fontWeight: 600,
                  borderRadius: 20,
                  background: activeCategory === cat ? 'var(--accent)' : 'var(--surface2)',
                  color: activeCategory === cat ? '#ffffff' : 'var(--text-dim)',
                  border: '1px solid var(--border)',
                  cursor: 'pointer',
                  transition: 'all 0.15s ease'
                }}
              >
                {cat}
              </button>
            ))}
          </div>
        ) : <div />}

        {/* Live Search */}
        <div style={{ position: 'relative', minWidth: 200 }}>
          <i className="fa-solid fa-magnifying-glass" style={{ position: 'absolute', left: 10, top: 9, color: 'var(--text-muted)', fontSize: 11 }}></i>
          <input
            type="text"
            placeholder={searchPlaceholder}
            value={search}
            onChange={(e) => { setSearch(e.target.value); setCurrentPage(1); }}
            style={{
              width: '100%',
              padding: '6px 10px 6px 28px',
              fontSize: 11,
              border: '1px solid var(--border)',
              borderRadius: 6,
              background: 'var(--surface2)',
              outline: 'none'
            }}
          />
        </div>
      </div>

      {/* Table Element */}
      <table>
        <thead>
          <tr>
            {columns.map((col) => (
              <th
                key={col.key}
                onClick={() => col.sortable && handleSort(col.key)}
                style={{
                  cursor: col.sortable ? 'pointer' : 'default',
                  textAlign: col.align || 'left',
                  width: col.width
                }}
              >
                <div style={{ display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                  <span>{col.header}</span>
                  {col.sortable && (
                    <span style={{ fontSize: 9, opacity: sortKey === col.key ? 1 : 0.4 }}>
                      {sortKey === col.key ? (sortOrder === 'asc' ? '▲' : '▼') : '⇅'}
                    </span>
                  )}
                </div>
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {paginatedData.length === 0 ? (
            <tr>
              <td colSpan={columns.length} style={{ textAlign: 'center', padding: 32, color: 'var(--text-muted)' }}>
                <i className="fa-solid fa-inbox" style={{ fontSize: 24, display: 'block', marginBottom: 6, opacity: 0.5 }}></i>
                {emptyMessage}
              </td>
            </tr>
          ) : (
            paginatedData.map((item, idx) => (
              <tr key={idx}>
                {columns.map((col) => (
                  <td key={col.key} style={{ textAlign: col.align || 'left' }}>
                    {col.render ? col.render(item) : item[col.key]}
                  </td>
                ))}
              </tr>
            ))
          )}
        </tbody>
      </table>

      {/* Pagination Controls */}
      {totalPages > 1 && (
        <div style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          marginTop: 12,
          paddingTop: 8,
          borderTop: '1px solid var(--border)',
          fontSize: 11,
          color: 'var(--text-muted)'
        }}>
          <div>
            Showing {(currentPage - 1) * pageSize + 1} to {Math.min(currentPage * pageSize, sortedData.length)} of {sortedData.length} records
          </div>
          <div style={{ display: 'flex', gap: 4 }}>
            <button
              disabled={currentPage === 1}
              onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
              className="btn btn-outline btn-sm"
              style={{ padding: '2px 8px', fontSize: 10 }}
            >
              Previous
            </button>
            <span style={{ padding: '3px 8px', fontWeight: 700, color: 'var(--accent)' }}>
              {currentPage} / {totalPages}
            </span>
            <button
              disabled={currentPage === totalPages}
              onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
              className="btn btn-outline btn-sm"
              style={{ padding: '2px 8px', fontSize: 10 }}
            >
              Next
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
