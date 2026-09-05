import React, { useState, useRef } from 'react';

interface FileDropzoneProps {
  label: string;
  hint?: string;
  accept?: string;
  onFileSelected: (file: File) => void;
  selectedFileName?: string;
}

export const FileDropzone: React.FC<FileDropzoneProps> = ({
  label,
  hint = 'PDF, DOCX, PNG, JPG up to 25MB',
  accept,
  onFileSelected,
  selectedFileName
}) => {
  const [isDragOver, setIsDragOver] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragOver(true);
  };

  const handleDragLeave = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragOver(false);
  };

  const handleDrop = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragOver(false);
    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
      onFileSelected(e.dataTransfer.files[0]);
    }
  };

  const handleClick = () => {
    fileInputRef.current?.click();
  };

  return (
    <div>
      <label style={{ fontSize: 10, fontWeight: 700, color: 'var(--text-muted)', textTransform: 'uppercase' }}>
        {label}
      </label>
      <div
        onDragOver={handleDragOver}
        onDragLeave={handleDragLeave}
        onDrop={handleDrop}
        onClick={handleClick}
        style={{
          border: isDragOver ? '2px dashed var(--accent)' : '2px dashed var(--border)',
          borderRadius: 8,
          padding: '18px 14px',
          textAlign: 'center',
          cursor: 'pointer',
          background: isDragOver ? 'var(--accent-light, #e0f2fe)' : 'var(--surface2)',
          transition: 'all 0.15s ease',
          marginTop: 4
        }}
      >
        <input
          ref={fileInputRef}
          type="file"
          accept={accept}
          style={{ display: 'none' }}
          onChange={(e) => {
            if (e.target.files && e.target.files.length > 0) {
              onFileSelected(e.target.files[0]);
            }
          }}
        />

        {selectedFileName ? (
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
            <i className="fa-solid fa-file-circle-check" style={{ color: 'var(--success, #059669)', fontSize: 20 }}></i>
            <div style={{ textAlign: 'left' }}>
              <div style={{ fontSize: 12, fontWeight: 700, color: 'var(--text)' }}>
                {selectedFileName}
              </div>
              <div style={{ fontSize: 10, color: 'var(--success, #059669)', fontWeight: 600 }}>
                File ready for secure upload · Click to change
              </div>
            </div>
          </div>
        ) : (
          <div>
            <i className="fa-solid fa-cloud-arrow-up" style={{ fontSize: 24, color: 'var(--accent)', marginBottom: 6 }}></i>
            <div style={{ fontSize: 12, fontWeight: 600, color: 'var(--text)' }}>
              Drag & Drop file here or <span style={{ color: 'var(--accent)', textDecoration: 'underline' }}>Browse</span>
            </div>
            <div style={{ fontSize: 10, color: 'var(--text-muted)', marginTop: 2 }}>
              {hint}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};
