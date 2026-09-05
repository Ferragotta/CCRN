import React, { useState } from 'react';

// 1. Interactive Bar Chart
export interface BarDataPoint {
  label: string;
  value: number;
  color?: string;
  subValue?: string;
}

export const InteractiveBarChart: React.FC<{
  data: BarDataPoint[];
  height?: number;
  maxValue?: number;
  unit?: string;
}> = ({ data, height = 200, maxValue = 100, unit = '%' }) => {
  const [hoveredIdx, setHoveredIdx] = useState<number | null>(null);

  return (
    <div style={{ width: '100%', position: 'relative' }}>
      <div style={{
        display: 'flex',
        alignItems: 'flex-end',
        justifyContent: 'space-between',
        gap: 12,
        height,
        paddingTop: 30,
        paddingBottom: 24,
        borderBottom: '1px solid var(--border)'
      }}>
        {data.map((item, idx) => {
          const heightPercent = Math.min(Math.max((item.value / maxValue) * 100, 4), 100);
          const isHovered = hoveredIdx === idx;
          const barColor = item.color || (item.value >= 80 ? 'var(--success)' : item.value >= 65 ? 'var(--warning)' : 'var(--danger)');

          return (
            <div
              key={idx}
              onMouseEnter={() => setHoveredIdx(idx)}
              onMouseLeave={() => setHoveredIdx(null)}
              style={{
                flex: 1,
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                height: '100%',
                justifyContent: 'flex-end',
                cursor: 'pointer',
                position: 'relative'
              }}
            >
              {/* Tooltip */}
              {isHovered && (
                <div style={{
                  position: 'absolute',
                  top: -28,
                  background: '#1e293b',
                  color: '#ffffff',
                  fontSize: 10,
                  fontWeight: 700,
                  padding: '3px 8px',
                  borderRadius: 4,
                  whiteSpace: 'nowrap',
                  zIndex: 10,
                  boxShadow: 'var(--shadow-md)',
                  animation: 'fadeIn 0.15s ease'
                }}>
                  {item.label}: {item.value}{unit}
                </div>
              )}

              {/* Bar */}
              <div
                style={{
                  width: '100%',
                  maxWidth: 42,
                  height: `${heightPercent}%`,
                  background: barColor,
                  borderRadius: '4px 4px 0 0',
                  transition: 'all 0.25s cubic-bezier(0.16, 1, 0.3, 1)',
                  opacity: hoveredIdx === null || isHovered ? 1 : 0.6,
                  transform: isHovered ? 'scaleY(1.04)' : 'scaleY(1)',
                  transformOrigin: 'bottom'
                }}
              />

              {/* X-Axis Label */}
              <div style={{
                position: 'absolute',
                bottom: -20,
                fontSize: 10,
                fontWeight: isHovered ? 700 : 500,
                color: isHovered ? 'var(--accent)' : 'var(--text-muted)',
                textAlign: 'center',
                whiteSpace: 'nowrap',
                overflow: 'hidden',
                textOverflow: 'ellipsis',
                maxWidth: 60
              }}>
                {item.label}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
};

// 2. Interactive Area Trend Chart
export interface TrendPoint {
  label: string;
  value: number;
}

export const InteractiveTrendChart: React.FC<{
  data: TrendPoint[];
  height?: number;
  strokeColor?: string;
  fillColor?: string;
}> = ({
  data,
  height = 160,
  strokeColor = '#0077b6',
  fillColor = 'rgba(0, 119, 182, 0.12)'
}) => {
  const [hoveredPoint, setHoveredPoint] = useState<{ point: TrendPoint; x: number; y: number } | null>(null);

  if (data.length === 0) return null;

  const maxVal = Math.max(...data.map(d => d.value), 10);
  const minVal = 0;
  const paddingX = 20;
  const paddingY = 25;
  const width = 500;
  const chartWidth = width - paddingX * 2;
  const chartHeight = height - paddingY * 2;

  const points = data.map((d, i) => {
    const x = paddingX + (i / (data.length - 1)) * chartWidth;
    const y = height - paddingY - ((d.value - minVal) / (maxVal - minVal)) * chartHeight;
    return { ...d, x, y };
  });

  const pathD = points.reduce((acc, p, i) => {
    return i === 0 ? `M ${p.x} ${p.y}` : `${acc} L ${p.x} ${p.y}`;
  }, '');

  const areaD = `${pathD} L ${points[points.length - 1].x} ${height - paddingY} L ${points[0].x} ${height - paddingY} Z`;

  return (
    <div style={{ width: '100%', position: 'relative' }}>
      <svg
        viewBox={`0 0 ${width} ${height}`}
        style={{ width: '100%', height, overflow: 'visible' }}
      >
        {/* Horizontal grid lines */}
        {[0, 0.5, 1].map((ratio, idx) => {
          const y = height - paddingY - ratio * chartHeight;
          return (
            <line
              key={idx}
              x1={paddingX}
              y1={y}
              x2={width - paddingX}
              y2={y}
              stroke="var(--border)"
              strokeDasharray="4 4"
            />
          );
        })}

        {/* Gradient fill */}
        <path d={areaD} fill={fillColor} />

        {/* Stroke line */}
        <path
          d={pathD}
          fill="none"
          stroke={strokeColor}
          strokeWidth="3"
          strokeLinecap="round"
          strokeLinejoin="round"
        />

        {/* Circles on points */}
        {points.map((p, idx) => (
          <g key={idx} style={{ cursor: 'pointer' }}>
            <circle
              cx={p.x}
              cy={p.y}
              r={hoveredPoint?.point.label === p.label ? 6 : 4}
              fill="#ffffff"
              stroke={strokeColor}
              strokeWidth="2.5"
              onMouseEnter={() => setHoveredPoint({ point: p, x: p.x, y: p.y })}
              onMouseLeave={() => setHoveredPoint(null)}
            />
          </g>
        ))}
      </svg>

      {/* Hover Tooltip */}
      {hoveredPoint && (
        <div style={{
          position: 'absolute',
          left: `${(hoveredPoint.x / width) * 100}%`,
          top: Math.max(hoveredPoint.y - 35, 0),
          transform: 'translateX(-50%)',
          background: '#1e293b',
          color: '#ffffff',
          fontSize: 10,
          fontWeight: 700,
          padding: '4px 8px',
          borderRadius: 4,
          whiteSpace: 'nowrap',
          zIndex: 10,
          boxShadow: 'var(--shadow-md)',
          pointerEvents: 'none'
        }}>
          {hoveredPoint.point.label}: {hoveredPoint.point.value} Cases
        </div>
      )}

      {/* X Labels */}
      <div style={{ display: 'flex', justifyContent: 'space-between', padding: '4px 10px 0 10px', fontSize: 10, color: 'var(--text-muted)' }}>
        {data.map((d, i) => (
          <span key={i}>{d.label}</span>
        ))}
      </div>
    </div>
  );
};

// 3. Radial Progress Gauge
export const RadialProgressGauge: React.FC<{
  percentage: number;
  label: string;
  sublabel?: string;
  size?: number;
  color?: string;
}> = ({
  percentage,
  label,
  sublabel,
  size = 140,
  color
}) => {
  const strokeWidth = 10;
  const radius = (size - strokeWidth) / 2;
  const circumference = 2 * Math.PI * radius;
  const strokeDashoffset = circumference - (percentage / 100) * circumference;
  const gaugeColor = color || (percentage >= 80 ? 'var(--success)' : percentage >= 60 ? 'var(--warning)' : 'var(--danger)');

  return (
    <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
      <div style={{ position: 'relative', width: size, height: size }}>
        <svg width={size} height={size} style={{ transform: 'rotate(-90deg)' }}>
          {/* Background circle */}
          <circle
            cx={size / 2}
            cy={size / 2}
            r={radius}
            stroke="var(--surface2)"
            strokeWidth={strokeWidth}
            fill="none"
          />
          {/* Progress circle */}
          <circle
            cx={size / 2}
            cy={size / 2}
            r={radius}
            stroke={gaugeColor}
            strokeWidth={strokeWidth}
            fill="none"
            strokeDasharray={circumference}
            strokeDashoffset={strokeDashoffset}
            strokeLinecap="round"
            style={{ transition: 'stroke-dashoffset 0.8s cubic-bezier(0.16, 1, 0.3, 1)' }}
          />
        </svg>

        <div style={{
          position: 'absolute',
          top: 0,
          left: 0,
          right: 0,
          bottom: 0,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'center',
          textAlign: 'center'
        }}>
          <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 28, fontWeight: 800, color: 'var(--text)', lineHeight: 1 }}>
            {percentage}%
          </div>
          <div style={{ fontSize: 9, color: 'var(--text-muted)', marginTop: 3, fontWeight: 600, textTransform: 'uppercase' }}>
            {label}
          </div>
        </div>
      </div>
      {sublabel && (
        <div style={{ fontSize: 11, color: 'var(--text-muted)', marginTop: 8, textAlign: 'center' }}>
          {sublabel}
        </div>
      )}
    </div>
  );
};
