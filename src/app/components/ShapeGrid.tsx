import { useEffect, useRef } from "react";
import "./ShapeGrid.css";

type ShapeGridDirection = "diagonal" | "up" | "right" | "down" | "left";
type ShapeGridShape = "square" | "hexagon" | "circle" | "triangle";

type ShapeGridProps = {
  direction?: ShapeGridDirection;
  speed?: number;
  borderColor?: string;
  squareSize?: number;
  hoverFillColor?: string;
  shape?: ShapeGridShape;
  hoverTrailAmount?: number;
  className?: string;
};

type GridCell = {
  x: number;
  y: number;
};

const getPositiveRemainder = (value: number, size: number) => ((value % size) + size) % size;

export default function ShapeGrid({
  direction = "right",
  speed = 1,
  borderColor = "#999",
  squareSize = 40,
  hoverFillColor = "#222",
  shape = "square",
  hoverTrailAmount = 0,
  className = "",
}: ShapeGridProps) {
  const canvasRef = useRef<HTMLCanvasElement | null>(null);
  const requestRef = useRef<number | null>(null);
  const gridOffset = useRef({ x: 0, y: 0 });
  const hoveredSquare = useRef<GridCell | null>(null);
  const trailCells = useRef<GridCell[]>([]);
  const cellOpacities = useRef(new Map<string, number>());

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return undefined;

    const context = canvas.getContext("2d");
    if (!context) return undefined;

    const isHexagon = shape === "hexagon";
    const isTriangle = shape === "triangle";
    const hexHorizontal = squareSize * 1.5;
    const hexVertical = squareSize * Math.sqrt(3);

    const resizeCanvas = () => {
      canvas.width = canvas.offsetWidth;
      canvas.height = canvas.offsetHeight;
    };

    const drawHexagon = (centerX: number, centerY: number, size: number) => {
      context.beginPath();

      for (let index = 0; index < 6; index += 1) {
        const angle = (Math.PI / 3) * index;
        const vertexX = centerX + size * Math.cos(angle);
        const vertexY = centerY + size * Math.sin(angle);

        if (index === 0) context.moveTo(vertexX, vertexY);
        else context.lineTo(vertexX, vertexY);
      }

      context.closePath();
    };

    const drawCircle = (centerX: number, centerY: number, size: number) => {
      context.beginPath();
      context.arc(centerX, centerY, size / 2, 0, Math.PI * 2);
      context.closePath();
    };

    const drawTriangle = (centerX: number, centerY: number, size: number, flip: boolean) => {
      context.beginPath();

      if (flip) {
        context.moveTo(centerX, centerY + size / 2);
        context.lineTo(centerX + size / 2, centerY - size / 2);
        context.lineTo(centerX - size / 2, centerY - size / 2);
      } else {
        context.moveTo(centerX, centerY - size / 2);
        context.lineTo(centerX + size / 2, centerY + size / 2);
        context.lineTo(centerX - size / 2, centerY + size / 2);
      }

      context.closePath();
    };

    const drawGrid = () => {
      context.clearRect(0, 0, canvas.width, canvas.height);

      if (isHexagon) {
        const columnShift = Math.floor(gridOffset.current.x / hexHorizontal);
        const offsetX = getPositiveRemainder(gridOffset.current.x, hexHorizontal);
        const offsetY = getPositiveRemainder(gridOffset.current.y, hexVertical);
        const columns = Math.ceil(canvas.width / hexHorizontal) + 3;
        const rows = Math.ceil(canvas.height / hexVertical) + 3;

        for (let column = -2; column < columns; column += 1) {
          for (let row = -2; row < rows; row += 1) {
            const centerX = column * hexHorizontal + offsetX;
            const centerY =
              row * hexVertical + ((column + columnShift) % 2 !== 0 ? hexVertical / 2 : 0) + offsetY;
            const cellKey = `${column},${row}`;
            const alpha = cellOpacities.current.get(cellKey);

            if (alpha) {
              context.globalAlpha = alpha;
              drawHexagon(centerX, centerY, squareSize);
              context.fillStyle = hoverFillColor;
              context.fill();
              context.globalAlpha = 1;
            }

            drawHexagon(centerX, centerY, squareSize);
            context.strokeStyle = borderColor;
            context.stroke();
          }
        }
      } else if (isTriangle) {
        const halfWidth = squareSize / 2;
        const columnShift = Math.floor(gridOffset.current.x / halfWidth);
        const rowShift = Math.floor(gridOffset.current.y / squareSize);
        const offsetX = getPositiveRemainder(gridOffset.current.x, halfWidth);
        const offsetY = getPositiveRemainder(gridOffset.current.y, squareSize);
        const columns = Math.ceil(canvas.width / halfWidth) + 4;
        const rows = Math.ceil(canvas.height / squareSize) + 4;

        for (let column = -2; column < columns; column += 1) {
          for (let row = -2; row < rows; row += 1) {
            const centerX = column * halfWidth + offsetX;
            const centerY = row * squareSize + squareSize / 2 + offsetY;
            const flip = ((column + columnShift + row + rowShift) % 2 + 2) % 2 !== 0;
            const cellKey = `${column},${row}`;
            const alpha = cellOpacities.current.get(cellKey);

            if (alpha) {
              context.globalAlpha = alpha;
              drawTriangle(centerX, centerY, squareSize, flip);
              context.fillStyle = hoverFillColor;
              context.fill();
              context.globalAlpha = 1;
            }

            drawTriangle(centerX, centerY, squareSize, flip);
            context.strokeStyle = borderColor;
            context.stroke();
          }
        }
      } else if (shape === "circle") {
        const offsetX = getPositiveRemainder(gridOffset.current.x, squareSize);
        const offsetY = getPositiveRemainder(gridOffset.current.y, squareSize);
        const columns = Math.ceil(canvas.width / squareSize) + 3;
        const rows = Math.ceil(canvas.height / squareSize) + 3;

        for (let column = -2; column < columns; column += 1) {
          for (let row = -2; row < rows; row += 1) {
            const centerX = column * squareSize + squareSize / 2 + offsetX;
            const centerY = row * squareSize + squareSize / 2 + offsetY;
            const cellKey = `${column},${row}`;
            const alpha = cellOpacities.current.get(cellKey);

            if (alpha) {
              context.globalAlpha = alpha;
              drawCircle(centerX, centerY, squareSize);
              context.fillStyle = hoverFillColor;
              context.fill();
              context.globalAlpha = 1;
            }

            drawCircle(centerX, centerY, squareSize);
            context.strokeStyle = borderColor;
            context.stroke();
          }
        }
      } else {
        const offsetX = getPositiveRemainder(gridOffset.current.x, squareSize);
        const offsetY = getPositiveRemainder(gridOffset.current.y, squareSize);
        const columns = Math.ceil(canvas.width / squareSize) + 3;
        const rows = Math.ceil(canvas.height / squareSize) + 3;

        for (let column = -2; column < columns; column += 1) {
          for (let row = -2; row < rows; row += 1) {
            const squareX = column * squareSize + offsetX;
            const squareY = row * squareSize + offsetY;
            const cellKey = `${column},${row}`;
            const alpha = cellOpacities.current.get(cellKey);

            if (alpha) {
              context.globalAlpha = alpha;
              context.fillStyle = hoverFillColor;
              context.fillRect(squareX, squareY, squareSize, squareSize);
              context.globalAlpha = 1;
            }

            context.strokeStyle = borderColor;
            context.strokeRect(squareX, squareY, squareSize, squareSize);
          }
        }
      }
    };

    const updateCellOpacities = () => {
      const targets = new Map<string, number>();

      if (hoveredSquare.current) {
        targets.set(`${hoveredSquare.current.x},${hoveredSquare.current.y}`, 1);
      }

      if (hoverTrailAmount > 0) {
        trailCells.current.forEach((cell, index) => {
          const key = `${cell.x},${cell.y}`;
          if (!targets.has(key)) {
            targets.set(key, (trailCells.current.length - index) / (trailCells.current.length + 1));
          }
        });
      }

      targets.forEach((_, key) => {
        if (!cellOpacities.current.has(key)) {
          cellOpacities.current.set(key, 0);
        }
      });

      cellOpacities.current.forEach((opacity, key) => {
        const target = targets.get(key) ?? 0;
        const next = opacity + (target - opacity) * 0.15;

        if (next < 0.005) cellOpacities.current.delete(key);
        else cellOpacities.current.set(key, next);
      });
    };

    const updateAnimation = () => {
      const effectiveSpeed = Math.max(speed, 0.1);
      const wrapX = isHexagon ? hexHorizontal * 2 : squareSize;
      const wrapY = isHexagon ? hexVertical : isTriangle ? squareSize * 2 : squareSize;

      switch (direction) {
        case "right":
          gridOffset.current.x = getPositiveRemainder(gridOffset.current.x - effectiveSpeed, wrapX);
          break;
        case "left":
          gridOffset.current.x = getPositiveRemainder(gridOffset.current.x + effectiveSpeed, wrapX);
          break;
        case "up":
          gridOffset.current.y = getPositiveRemainder(gridOffset.current.y + effectiveSpeed, wrapY);
          break;
        case "down":
          gridOffset.current.y = getPositiveRemainder(gridOffset.current.y - effectiveSpeed, wrapY);
          break;
        case "diagonal":
          gridOffset.current.x = getPositiveRemainder(gridOffset.current.x - effectiveSpeed, wrapX);
          gridOffset.current.y = getPositiveRemainder(gridOffset.current.y - effectiveSpeed, wrapY);
          break;
      }

      updateCellOpacities();
      drawGrid();
      requestRef.current = requestAnimationFrame(updateAnimation);
    };

    const pushTrailCell = () => {
      if (!hoveredSquare.current || hoverTrailAmount <= 0) return;

      trailCells.current.unshift({ ...hoveredSquare.current });
      if (trailCells.current.length > hoverTrailAmount) {
        trailCells.current.length = hoverTrailAmount;
      }
    };

    const setHoveredSquare = (cell: GridCell) => {
      if (hoveredSquare.current?.x === cell.x && hoveredSquare.current?.y === cell.y) return;

      pushTrailCell();
      hoveredSquare.current = cell;
    };

    const handleMouseMove = (event: MouseEvent) => {
      const rect = canvas.getBoundingClientRect();
      const mouseX = event.clientX - rect.left;
      const mouseY = event.clientY - rect.top;

      if (isHexagon) {
        const columnShift = Math.floor(gridOffset.current.x / hexHorizontal);
        const offsetX = getPositiveRemainder(gridOffset.current.x, hexHorizontal);
        const offsetY = getPositiveRemainder(gridOffset.current.y, hexVertical);
        const adjustedX = mouseX - offsetX;
        const adjustedY = mouseY - offsetY;
        const column = Math.round(adjustedX / hexHorizontal);
        const rowOffset = (column + columnShift) % 2 !== 0 ? hexVertical / 2 : 0;
        const row = Math.round((adjustedY - rowOffset) / hexVertical);

        setHoveredSquare({ x: column, y: row });
      } else if (isTriangle) {
        const halfWidth = squareSize / 2;
        const offsetX = getPositiveRemainder(gridOffset.current.x, halfWidth);
        const offsetY = getPositiveRemainder(gridOffset.current.y, squareSize);
        const adjustedX = mouseX - offsetX;
        const adjustedY = mouseY - offsetY;

        setHoveredSquare({
          x: Math.round(adjustedX / halfWidth),
          y: Math.floor(adjustedY / squareSize),
        });
      } else if (shape === "circle") {
        const offsetX = getPositiveRemainder(gridOffset.current.x, squareSize);
        const offsetY = getPositiveRemainder(gridOffset.current.y, squareSize);
        const adjustedX = mouseX - offsetX;
        const adjustedY = mouseY - offsetY;

        setHoveredSquare({
          x: Math.round(adjustedX / squareSize),
          y: Math.round(adjustedY / squareSize),
        });
      } else {
        const offsetX = getPositiveRemainder(gridOffset.current.x, squareSize);
        const offsetY = getPositiveRemainder(gridOffset.current.y, squareSize);
        const adjustedX = mouseX - offsetX;
        const adjustedY = mouseY - offsetY;

        setHoveredSquare({
          x: Math.floor(adjustedX / squareSize),
          y: Math.floor(adjustedY / squareSize),
        });
      }
    };

    const handleMouseLeave = () => {
      pushTrailCell();
      hoveredSquare.current = null;
    };

    resizeCanvas();
    window.addEventListener("resize", resizeCanvas);
    canvas.addEventListener("mousemove", handleMouseMove);
    canvas.addEventListener("mouseleave", handleMouseLeave);
    requestRef.current = requestAnimationFrame(updateAnimation);

    return () => {
      window.removeEventListener("resize", resizeCanvas);
      canvas.removeEventListener("mousemove", handleMouseMove);
      canvas.removeEventListener("mouseleave", handleMouseLeave);

      if (requestRef.current) {
        cancelAnimationFrame(requestRef.current);
      }
    };
  }, [direction, speed, borderColor, hoverFillColor, squareSize, shape, hoverTrailAmount]);

  return <canvas ref={canvasRef} className={`shapegrid-canvas ${className}`} />;
}
