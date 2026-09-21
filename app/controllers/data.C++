from collections import deque

queue = deque()
queue.append("Antrean 1")  # Enqueue
queue.append("Antrean 2")

print(queue.popleft())  # Dequeue -> Output: Antrean 1
